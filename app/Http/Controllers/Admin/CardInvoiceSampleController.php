<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CardInvoiceSample\CreateCardInvoiceRequest;
use App\Http\Requests\Admin\CardInvoiceSample\UpdateCardInvoiceRequest;
use App\Models\CardInvoice;
use App\Models\CardInvoiceTypeItem;
use App\Models\CardInvoiceWinchRequest;
use App\Models\EmployeeData;
use App\Models\Part;
use App\Models\PointRule;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\ServiceType;
use App\Models\Setting;
use App\Models\SparePart;
use App\Models\TaxesFees;
use App\Models\WorkCard;
use App\Services\CardInvoiceSampleServices;
use App\Services\GoogleMapServices;
use App\Services\NotificationServices;
use App\Services\PointsServices;
use App\Services\SampleSalesInvoiceServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CardInvoiceSampleController extends Controller
{
    use CardInvoiceSampleServices, SampleSalesInvoiceServices, GoogleMapServices, NotificationServices, PointsServices;

    public function create(WorkCard $work_card)
    {
        $branch = $work_card->branch;

        $servicesTypes = ServiceType::where('status', 1)->where('branch_id', $branch->id)->get();

        $services = Service::where('status', 1)->where('branch_id', $branch->id)->get();

        $packages = ServicePackage::orderBy('id', 'DESC')->where('branch_id', $branch->id)->get();

        $sparPartsTypes = SparePart::where('status', 1)->where('branch_id', $branch->id)->get();

        $employees = EmployeeData::whereHas('employeeSetting', function ($q) {
            $q->where('service_status', 1);
        })->get();

        $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })->get();

        $taxes = TaxesFees::where('active_services', 1)->where('branch_id', $branch->id)->get();

        $data = [];

        if ($work_card->cardInvoice) {

            $data['cardInvoiceServiceType'] = $work_card->cardInvoice->types()->where('type', 'Service')->first();
            $data['cardInvoicePartType'] = $work_card->cardInvoice->types()->where('type', 'Part')->first();
            $data['cardInvoicePackageType'] = $work_card->cardInvoice->types()->where('type', 'Package')->first();
            $data['cardInvoiceWinchType'] = $work_card->cardInvoice->types()->where('type', 'Winch')->first();
        }

        $setting = Setting::where('branch_id', $branch->id)->first();

        $branch_lat = $setting ? $setting->lat : '';
        $branch_long = $setting ? $setting->long : '';
        $kilo_meter_price = $setting ? $setting->kilo_meter_price : 0;

        $create_url = route('admin:work.cards.invoice.sample.store', $work_card->id);

        $update_url = $work_card->cardInvoice ?
            route('admin:work.cards.invoices.sample.update',
                ['work_card' => $work_card->id, 'cardInvoice' => $work_card->cardInvoice->id]) : '';

        $url = $work_card->cardInvoice ? $update_url : $create_url;

        $rulePoints = 0;

        if ($work_card->cardInvoice) {

            $pointsRule = PointRule::find($work_card->cardInvoice->points_rule_id);

            $rulePoints += $pointsRule ? $pointsRule->points : 0;
        }

        $customerPoints = $work_card->customer ? $work_card->customer->points + $rulePoints : 0;

        $pointsRules = PointRule::where('status', 1)->where('branch_id', $branch->id)->where('points', '<=', $customerPoints)->get();

        return view('admin.work_cards.invoices.sample.create',
            compact('work_card', 'services', 'packages', 'sparPartsTypes', 'employees', 'parts',
                'taxes', 'servicesTypes', 'data', 'url', 'branch_long', 'branch_lat', 'kilo_meter_price', 'pointsRules'));
    }

    public function store(CreateCardInvoiceRequest $request)
    {

        if (!$request->has('parts') && !$request->has('services') && !$request->has('packages') && !$request->has('active_winch_box')) {

            return redirect()->back()->with(['message' => __('sorry please select one item at least'), 'alert-type' => 'error']);
        }

        $work_card = WorkCard::find($request['work_card_id']);

        $branch_id = $work_card->branch_id;

        if ($request->has('active_winch_box')) {

            $setting = Setting::where('branch_id', $branch_id)->first();

            if (!$setting) {

                return redirect()->back()->with(['message' => __('sorry please prepare branch setting'), 'alert-type' => 'error']);
            }
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $invoice_data = $this->prepareCardInvoice($data, $branch_id, $work_card);

            $invoice_data['type'] = $request['type'];

            $invoice_data['invoice_number'] = $this->generateInvoiceNumber($branch_id, 'App\Models\CardInvoice', 'invoice_number');

            $card_invoice = CardInvoice::create($invoice_data);

            $this->handlePointsLog($card_invoice);

            $this->updateCardInvoice($work_card);

//           Service data
            if ($request->has('services')) {

                $card_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Service');

                foreach ($data['services'] as $index => $service) {

                    $item_data = $this->prepareServices($service);
                    $item_data['card_invoice_type_id'] = $card_invoice_type->id;
                    $service_item = CardInvoiceTypeItem::create($item_data);

                    if (isset($service['employees'])) {

                        $this->servicesEmployees($service_item, $service['employees']);
                    }
                }
            }

//           Packages data
            if ($request->has('packages')) {

                $package_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Package');

                foreach ($data['packages'] as $index => $package) {

                    $item_data = $this->preparePackages($package);

                    $item_data['card_invoice_type_id'] = $package_invoice_type->id;
                    $package_item = CardInvoiceTypeItem::create($item_data);

                    if (isset($package['employees'])) {

                        $this->packageEmployees($package_item, $package['employees']);
                    }
                }
            }

//          parts data
            if ($request->has('parts')) {

                $part_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Part');

                foreach ($data['parts'] as $index => $item) {

                    $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($branch_id);

                    $part = Part::find($item['id']);

                    $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $branch_id,
                        $item['purchase_invoice_id'], $item['id']);

                    if (!$purchase_invoice) {
                        return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                    }

                    $invoice_item = $purchase_invoice->items()->where('part_id', $item['id'])->first();

                    if ($part->quantity < $item['qty']) {
                        return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                    }

//                  REPEAT PART ITEM
                    if ($invoice_item->purchase_qty < $item['qty']) {

                        $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $branch_id,
                            $purchase_invoice->id, $item['id']);

                        $this->repeatPartItem($item, $next_purchase_invoices, $part_invoice_type);

                        continue;
                    }

                    $item_data = $this->prepareParts($item);

                    $item_data['purchase_invoice_id'] = $purchase_invoice->id;

                    $item_data['card_invoice_type_id'] = $part_invoice_type->id;

                    $part_item = CardInvoiceTypeItem::create($item_data);

                    $this->affectedPurchaseItem($invoice_item, $item['qty']);

                    $this->affectedPart($item['id'], $item['qty'], $item['price']);
                }
            }

//           Winch data
            if ($request->has('active_winch_box')) {

                $card_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Winch');

                $item_data = $this->prepareWinchRequests($data, $branch_id);
                $item_data['card_invoice_type_id'] = $card_invoice_type->id;

                $winch_data = CardInvoiceWinchRequest::create($item_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        try {

            $this->sendNotification('work_card_status_to_user', 'admins',
                [
                    'work_card' => $work_card,
                    'message' => 'Work Card Status updated to Processing'
                ]);

            $this->sendNotification('work_card_status_to_customer', 'customer',
                [
                    'work_card' => $work_card,
                    'message' => 'Work Card Status updated to Processing'
                ]);

        } catch (\Exception $e) {

            if ($request['save_type'] == 'save_and_print') {

                $url = route('admin:work-cards.index', ['print_type' => 'print', 'work_card' => $work_card->id]);
                return redirect($url)->with(['message' => __('words.card-invoice-updated'), 'alert-type' => 'success']);
            }

            return redirect()->back()->with(['message' => __('words.card-invoice-created'), 'alert-type' => 'success']);
        }

        if ($request['save_type'] == 'save_and_print') {

            $url = route('admin:work-cards.index', ['print_type' => 'print', 'work_card' => $work_card->id]);
            return redirect($url)->with(['message' => __('words.card-invoice-updated'), 'alert-type' => 'success']);
        }

        return redirect()->back()->with(['message' => __('words.card-invoice-created'), 'alert-type' => 'success']);
    }

    public function update(UpdateCardInvoiceRequest $request, WorkCard $workCard, CardInvoice $cardInvoice)
    {

        if ($cardInvoice->RevenueReceipts->count()) {
            return redirect()->back()->with(['message' => __('words.card-invoice-paid'), 'alert-type' => 'error']);
        }

        if (!$request->has('parts') && !$request->has('services') && !$request->has('packages') && !$request->has('active_winch_box')) {

            return redirect()->back()->with(['message' => __('sorry please select one item at least'), 'alert-type' => 'error']);
        }

        $branch_id = $workCard->branch_id;

        if ($request->has('active_winch_box')) {

            $setting = Setting::where('branch_id', $branch_id)->first();

            if (!$setting) {

                return redirect()->back()->with(['message' => __('sorry please prepare branch setting'), 'alert-type' => 'error']);
            }
        }

        try {

            DB::beginTransaction();

            if ($cardInvoice->types) {
                $this->reset($cardInvoice);
            }

            $data = $request->all();

            $invoice_data = $this->prepareCardInvoice($data, $branch_id, $workCard);

            $cardInvoice->update($invoice_data);

            $this->handlePointsLog($cardInvoice);

//           Service data
            if ($request->has('services')) {

                $card_invoice_type = $this->createCardInvoiceSampleType($cardInvoice->id, 'Service');

                foreach ($data['services'] as $index => $service) {

                    $item_data = $this->prepareServices($service);
                    $item_data['card_invoice_type_id'] = $card_invoice_type->id;
                    $service_item = CardInvoiceTypeItem::create($item_data);

                    if (isset($service['employees'])) {

                        $this->servicesEmployees($service_item, $service['employees']);
                    }
                }
            }

//           Packages data
            if ($request->has('packages')) {

                $package_invoice_type = $this->createCardInvoiceSampleType($cardInvoice->id, 'Package');

                foreach ($data['packages'] as $index => $package) {

                    $item_data = $this->preparePackages($package);

                    $item_data['card_invoice_type_id'] = $package_invoice_type->id;
                    $package_item = CardInvoiceTypeItem::create($item_data);

                    if (isset($package['employees'])) {

                        $this->packageEmployees($package_item, $package['employees']);
                    }
                }
            }

//          parts data
            if ($request->has('parts')) {

                $part_invoice_type = $this->createCardInvoiceSampleType($cardInvoice->id, 'Part');

                foreach ($data['parts'] as $index => $item) {

                    $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($branch_id);

                    $part = Part::find($item['id']);

                    $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $branch_id,
                        $item['purchase_invoice_id'], $item['id']);

                    if (!$purchase_invoice) {
                        return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                    }

                    $invoice_item = $purchase_invoice->items()->where('part_id', $item['id'])->first();

                    if ($part->quantity < $item['qty']) {
                        return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                    }

//                  REPEAT PART ITEM
                    if ($invoice_item->purchase_qty < $item['qty']) {

                        $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $branch_id,
                            $purchase_invoice->id, $item['id']);

                        $this->repeatPartItem($item, $next_purchase_invoices, $part_invoice_type);

                        continue;
                    }

                    $item_data = $this->prepareParts($item);

                    $item_data['purchase_invoice_id'] = $purchase_invoice->id;

                    $item_data['card_invoice_type_id'] = $part_invoice_type->id;

                    $part_item = CardInvoiceTypeItem::create($item_data);

                    $this->affectedPurchaseItem($invoice_item, $item['qty']);

                    $this->affectedPart($item['id'], $item['qty'], $item['price']);
                }
            }


//           Winch data
            if ($request->has('active_winch_box')) {

                $card_invoice_type = $this->createCardInvoiceSampleType($cardInvoice->id, 'Winch');

                $item_data = $this->prepareWinchRequests($data, $branch_id);
                $item_data['card_invoice_type_id'] = $card_invoice_type->id;

                $winch_data = CardInvoiceWinchRequest::create($item_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        if ($request['save_type'] == 'save_and_print') {

            $url = route('admin:work-cards.index', ['print_type' => 'print', 'work_card' => $workCard->id]);
            return redirect($url)->with(['message' => __('words.card-invoice-updated'), 'alert-type' => 'success']);
        }

        return redirect()->back()->with(['message' => __('words.card-invoice-created'), 'alert-type' => 'success']);

    }

    public function getDistance(Request $request)
    {

        try {

            $auth = auth()->user();

            $branch_id = $auth->branch_id;

            $setting = Setting::where('branch_id', $branch_id)->first();

            if (!$setting) {
                return response()->json('sorry please update setting', 400);
            }

            $branch_lat = $setting->lat;
            $branch_long = $setting->long;
            $kilo_meter_price = $setting->kilo_meter_price;

            $distance = $this->calculateDistanceBetweenTwoAddresses($branch_lat, $branch_long, $request['lat'], $request['long'], '3959');

            $total = round($kilo_meter_price * $distance, 2);

        } catch (\Exception $e) {

            return response()->json('sorry something went wrong', 400);
        }

        return response()->json(['total' => $total, 'distance' => $distance], 200);
    }
}
