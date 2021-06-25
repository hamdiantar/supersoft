<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CardInvoice\CreateCardInvoiceRequest;
use App\Models\CardInvoice;
use App\Models\CardInvoiceType;
use App\Models\CardInvoiceTypeItem;
use App\Models\CardInvoiceWinchRequest;
use App\Models\EmployeeData;
use App\Models\MaintenanceDetectionType;
use App\Models\Part;
use App\Models\PointRule;
use App\Models\PurchaseInvoice;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\ServiceType;
use App\Models\Setting;
use App\Models\SparePart;
use App\Models\TaxesFees;
use App\Models\User;
use App\Models\WorkCard;
use App\Notifications\CustomerWorkCardStatusNotification;
use App\Notifications\WorkCardStatusNotification;
use App\Services\CardInvoiceServices;
use App\Services\GoogleMapServices;
use App\Services\NotificationServices;
use App\Services\PointsServices;
use App\Services\SampleSalesInvoiceServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class CardInvoicesController extends Controller
{
    use CardInvoiceServices, SampleSalesInvoiceServices, GoogleMapServices, NotificationServices, PointsServices;

    public function create(WorkCard $work_card)
    {

        if ($work_card->cardInvoice) {

            $card_invoice = $work_card->cardInvoice;

            return redirect(route('admin:work.cards.invoices.edit',
                ['work_card' => $work_card->id, 'card_invoice' => $card_invoice->id]))
                ->with(['message' => __('words.card-invoice-prepared'), 'alert-type' => 'success']);
        }

        $branch = $work_card->branch;

        $maintenance_types = MaintenanceDetectionType::where('branch_id', $branch->id)->get();

        return view('admin.work_cards.invoices.create', compact('maintenance_types', 'work_card'));
    }

    public function store(CreateCardInvoiceRequest $request)
    {

        try {
            DB::beginTransaction();

            $work_card = WorkCard::findOrFail($request['work_card_id']);

            $branch_id = $work_card->branch_id;

            $data = $request->only('work_card_id', 'date', 'time', 'terms');

            $data['created_by'] = auth()->id();

            $data['type'] = 'cash';

            $data['invoice_number'] = $this->generateInvoiceNumber($branch_id, 'App\Models\CardInvoice', 'invoice_number');

            $card_invoice = CardInvoice::create($data);

            $this->updateCardInvoice($work_card);

            foreach ($request['maintenance_types'] as $maintenance_type) {

                $card_invoice->maintenanceDetectionTypes()->attach($maintenance_type);

                foreach ($request['type-' . $maintenance_type]['maintenance_type_parts'] as $maintenance_type_part) {

                    $notes = $request['notes_' . $maintenance_type_part];
                    $degree = $request['degree_' . $maintenance_type_part] ? $request['degree_' . $maintenance_type_part] : 1;
                    $images = $request['image_' . $maintenance_type_part];

                    $images_links = [];

                    if ($request->has('image_' . $maintenance_type_part) && is_array($request['image_' . $maintenance_type_part])) {

                        foreach ($images as $image) {
                            $images_links[] = uploadImage($image, 'maintenance_parts');
                        }
                    }

                    $card_invoice->maintenanceDetections()->attach($maintenance_type_part,
                        ['images' => json_encode($images_links), 'notes' => $notes, 'degree' => $degree,
                            'maintenance_type_id' => $maintenance_type]);

                }
            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.try-again'), 'alert-type' => 'error']);
        }

        $url = route('admin:work.cards.invoices.edit', ['work_card' => $work_card->id, 'card_invoice' => $card_invoice->id]);

        if ($request['save_type'] == 'save_and_print') {

            $url = route('admin:work-cards.index', ['print_type' => 'print', 'work_card' => $work_card->id]);
        }

        return redirect($url)->with(['message' => __('words.card-invoice-created'), 'alert-type' => 'success']);
    }

    public function updateCardInvoice($work_card)
    {
        if ($work_card->status == 'pending') {

            $work_card->status = 'processing';
            $work_card->save();

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
        }
    }

    public function validation(Request $request)
    {

        $rules = $this->rules($request);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        return response()->json(true, 200);
    }

    public function edit(WorkCard $work_card, CardInvoice $card_invoice)
    {
        $work_card = $card_invoice->workCard;

        $branch = $work_card->branch;

        $maintenance_types = MaintenanceDetectionType::where('branch_id', $branch->id)->get();

        $card_invoice_type_ids = $card_invoice->maintenanceDetectionTypes->pluck('id')->toArray();

        $card_invoice_parts_ids = $card_invoice->maintenanceDetections->pluck('id')->toArray();

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

        $setting = Setting::where('branch_id', $branch->id)->first();

        $branch_lat = $setting ? $setting->lat : '';
        $branch_long = $setting ? $setting->long : '';
        $kilo_meter_price = $setting ? $setting->kilo_meter_price : 0;

        $winchType = CardInvoiceType::where('card_invoice_id', $card_invoice->id)->where('type', 'Winch')->first();

        $rulePoints = 0;

        if ($work_card->cardInvoice) {

            $pointsRule = PointRule::find($work_card->cardInvoice->points_rule_id);

            $rulePoints += $pointsRule ? $pointsRule->points : 0;
        }

        $customerPoints = $work_card->customer ? $work_card->customer->points + $rulePoints : 0;

        $pointsRules = PointRule::where('status', 1)->where('branch_id', $branch->id)->where('points', '<=', $customerPoints)->get();

        return view('admin.work_cards.invoices.edit',
            compact('maintenance_types', 'work_card', 'card_invoice', 'sparPartsTypes', 'parts',
                'card_invoice_type_ids', 'card_invoice_parts_ids', 'services', 'servicesTypes', 'packages',
                'taxes', 'employees', 'branch_lat', 'branch_long', 'kilo_meter_price', 'winchType', 'pointsRules', 'customerPoints'));
    }

    public function updateValidation(Request $request)
    {
        $rules = $this->rulesInUpdate($request);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        return response()->json(true, 200);
    }

    public function update(WorkCard $work_card, CardInvoice $card_invoice, Request $request)
    {

        $data = $request->all();

        $work_card = WorkCard::findOrFail($data['work_card_id']);

        $branch_id = $work_card->branch_id;

        if ($card_invoice->RevenueReceipts->count()) {
            return redirect()->back()->with(['message' => __('words.card-invoice-paid'), 'alert-type' => 'error']);
        }

        if ($request->has('active_winch_box')) {

            $setting = Setting::where('branch_id', $branch_id)->first();

            if (!$setting) {

                return redirect()->back()->with(['message' => __('sorry please prepare branch setting'), 'alert-type' => 'error']);
            }
        }

        try {
            DB::beginTransaction();

            if ($card_invoice->types) {
                $this->reset($card_invoice);
            }

            $invoice_data = $this->prepareCardInvoiceData($data, $branch_id, $work_card);

            $card_invoice->update($invoice_data);

            $this->handlePointsLog($card_invoice);

            foreach ($data['maintenance_types'] as $maintenance_type) {

                $card_invoice->maintenanceDetectionTypes()->detach($maintenance_type);

                $card_invoice->maintenanceDetectionTypes()
                    ->attach($maintenance_type,
                        [
                            'sub_total' => $request['maintenance_type_' . $maintenance_type]['sub_total'],
                            'discount_type' => $request['maintenance_type_' . $maintenance_type]['discount_type'],
                            'discount' => $request['maintenance_type_' . $maintenance_type]['discount'],
                            'total_after_discount' => $request['maintenance_type_' . $maintenance_type]['total_after_discount'],
                        ]);

                foreach ($request['type-' . $maintenance_type]['maintenance_type_parts'] as $maintenance_type_part) {

                    $notes = $request['notes_' . $maintenance_type_part];
                    $degree = $request['degree_' . $maintenance_type_part] ? $request['degree_' . $maintenance_type_part] : 1;
                    $images = $request['image_' . $maintenance_type_part];

                    $attributes = ['notes' => $notes, 'degree' => $degree, 'maintenance_type_id' => $maintenance_type];

                    $images_links = [];

                    if ($request->has('image_' . $maintenance_type_part) && is_array($request['image_' . $maintenance_type_part])) {

                        $this->deleteOldImages($card_invoice, $maintenance_type_part);

                        foreach ($images as $image) {
                            $images_links[] = uploadImage($image, 'maintenance_parts');
                        }

                        $attributes['images'] = json_encode($images_links);
                    }

                    $card_invoice->maintenanceDetections()->syncWithoutDetaching([$maintenance_type_part => $attributes]);

//                  Service data
                    if ($request->has('service_ids_' . $maintenance_type_part)) {

                        $card_invoice_type = $this->createCardInvoiceType($card_invoice->id, $maintenance_type_part, 'Service');

                        foreach ($request['service_ids_' . $maintenance_type_part] as $index => $service_id) {

                            $item_data = $this->prepareServices($request, $maintenance_type_part, $service_id);
                            $item_data['card_invoice_type_id'] = $card_invoice_type->id;
                            $service_item = CardInvoiceTypeItem::create($item_data);

                            $this->servicesEmployees($request, $service_item, $service_id, $maintenance_type_part);

                        }
                    }

//                  Packages data
                    if ($request->has('package_ids_' . $maintenance_type_part)) {

                        $package_invoice_type = $this->createCardInvoiceType($card_invoice->id, $maintenance_type_part, 'Package');

                        foreach ($request['package_ids_' . $maintenance_type_part] as $index => $package_id) {

                            $item_data = $this->preparePackages($request, $maintenance_type_part, $package_id);
                            $item_data['card_invoice_type_id'] = $package_invoice_type->id;
                            $package_item = CardInvoiceTypeItem::create($item_data);

                            $this->packageEmployees($request, $package_item, $package_id, $maintenance_type_part);
                        }
                    }

//                    parts data
                    if ($request->has('part_ids_' . $maintenance_type_part)) {

                        $part_invoice_type = $this->createCardInvoiceType($card_invoice->id, $maintenance_type_part, 'Part');

                        foreach ($request['part_ids_' . $maintenance_type_part] as $index => $part_id) {

                            $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($branch_id);

                            $part = Part::find($part_id);

                            $part_index = $request['part_index_' . $maintenance_type_part][$index];

                            $requestName = 'part_' . $part_id . '_maintenance_' . $maintenance_type_part . '_index_' . $part_index;

                            $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $branch_id,
                                $request[$requestName]['purchase_invoice_id'], $part_id);

                            if (!$purchase_invoice) {
                                return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                            }

                            $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

                            if ($part->quantity < $request[$requestName]['qty']) {
                                return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                            }

//                           REPEAT PART ITEM
                            if ($invoice_item->purchase_qty < $request[$requestName]['qty']) {

                                $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $branch_id,
                                    $purchase_invoice->id, $part_id);

                                $this->repeatPartItem($data, $next_purchase_invoices, $maintenance_type_part, $part_id, $part_invoice_type, $index);


                                continue;
                            }

                            $item_data = $this->prepareParts($data, $maintenance_type_part, $part_id, $index);

                            $item_data['purchase_invoice_id'] = $purchase_invoice->id;

                            $item_data['card_invoice_type_id'] = $part_invoice_type->id;

                            $part_item = CardInvoiceTypeItem::create($item_data);

                            $this->affectedPurchaseItem($invoice_item, $request[$requestName]['qty']);

                            $this->affectedPart($part_id, $request[$requestName]['qty'], $request[$requestName]['price']);
                        }
                    }
                }
            }

//          Winch data
            if ($request->has('active_winch_box')) {

                $card_invoice_type = $this->createCardInvoiceType($card_invoice->id, null, 'Winch');

                $item_data = $this->prepareWinchRequests($request, $branch_id);

                $item_data['card_invoice_type_id'] = $card_invoice_type->id;

                $winch_data = CardInvoiceWinchRequest::create($item_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.try-again'), 'alert-type' => 'error']);
        }

        if ($request['save_type'] == 'save_and_print') {

            $url = route('admin:work-cards.index', ['print_type' => 'print', 'work_card' => $work_card->id]);
            return redirect($url)->with(['message' => __('words.card-invoice-updated'), 'alert-type' => 'success']);
        }

        return redirect()->back()->with(['message' => __('words.card-invoice-updated'), 'alert-type' => 'success']);
    }

    public function revenueReceipts(CardInvoice $card_invoice)
    {

        if ($card_invoice->remaining <= 0) {

            $card_invoice->workCard->status = 'finished';
            $card_invoice->workCard->save();

        } else {

            $card_invoice->workCard->status = 'processing';
            $card_invoice->workCard->save();
        }

        $invoice = $card_invoice;
        return view('admin.work_cards.revenue_receipts.index', compact('invoice'));
    }

    public function showImages(Request $request)
    {

        $card_invoice = CardInvoice::findOrFail($request['card_invoice_id']);

        $maintenance_part = $card_invoice->maintenanceDetections()
            ->wherePivot('maintenance_detection_id', $request['maintenance_id'])
            ->first();

        $images = [];

        if ($maintenance_part) {
            $images = json_decode($maintenance_part->pivot->images);
        }

        return view('admin.work_cards.invoices.ajax_show_images', compact('images'));
    }

    public function deleteOldImages($card_invoice, $maintenance_id)
    {

        $maintenance_part = $card_invoice->maintenanceDetections()
            ->wherePivot('maintenance_detection_id', $maintenance_id)
            ->first();

        $images = [];

        if ($maintenance_part) {
            $images = json_decode($maintenance_part->pivot->images);
        }

        foreach ($images as $image) {

            $path = public_path('storage/images/maintenance_parts/' . $image);

            if (File::exists($path)) {
                File::delete($path);
            }
        }
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
