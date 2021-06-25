<?php

namespace App\Http\Controllers\Admin;

use App\Models\CardInvoice;
use App\Models\CardInvoiceTypeItem;
use App\Models\CardInvoiceWinchRequest;
use App\Models\Part;
use App\Models\Quotation;
use App\Services\CardInvoiceSampleServices;
use App\Services\GoogleMapServices;
use App\Services\NotificationServices;
use App\Services\QuotationToWorkCardServices;
use App\Services\SampleSalesInvoiceServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class QuotationToWorkCardController extends Controller
{
    use CardInvoiceSampleServices, SampleSalesInvoiceServices, GoogleMapServices, NotificationServices;

    use QuotationToWorkCardServices;

    public function quotationToWorkCard(Request $request)
    {
        $this->validate($request, [
            'quotation_id' => 'required|integer|exists:quotations,id',
            'car_id' => 'required|integer|exists:cars,id'
        ]);

        $quotation = Quotation::find($request['quotation_id']);

        $branch_id = $quotation->branch_id;

        try {
            DB::beginTransaction();

            $workCard = $this->createWorkCard($quotation, $request['car_id']);

            $data = $this->prepareQuotationData($quotation, $workCard);

            $invoice_data = $this->prepareCardInvoice($data, $branch_id, $workCard);

            $invoice_data['type'] = $data['type'];

            $invoice_data['invoice_number'] = $this->generateInvoiceNumber($branch_id, 'App\Models\CardInvoice', 'invoice_number');

            $card_invoice = CardInvoice::create($invoice_data);

            $this->updateCardInvoice($workCard);

//           Service data
            if (isset($data['services'])) {

                $card_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Service');

                foreach ($data['services'] as $index => $service) {

                    $item_data = $this->prepareServices($service);

                    $item_data['card_invoice_type_id'] = $card_invoice_type->id;

                    CardInvoiceTypeItem::create($item_data);
                }
            }

//           Packages data
            if (isset($data['packages'])) {

                $package_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Package');

                foreach ($data['packages'] as $index => $package) {

                    $item_data = $this->preparePackages($package);

                    $item_data['card_invoice_type_id'] = $package_invoice_type->id;

                    CardInvoiceTypeItem::create($item_data);
                }
            }

//          parts data
            if (isset($data['parts'])) {

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
            if (isset($data['active_winch_box'])) {

                $card_invoice_type = $this->createCardInvoiceSampleType($card_invoice->id, 'Winch');

                $item_data = $this->prepareWinchRequests($data, $branch_id);

                $item_data['card_invoice_type_id'] = $card_invoice_type->id;

                CardInvoiceWinchRequest::create($item_data);
            }


            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

//            dd($e->getMessage());

            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        try {

            $this->sendNotification('work_card_status_to_user','admins',
                [
                    'work_card' => $workCard,
                    'message'=> 'Work Card Status updated to Processing'
                ]);

            $this->sendNotification('work_card_status_to_customer','customer',
                [
                    'work_card' => $workCard,
                    'message'=> 'Work Card Status updated to Processing'
                ]);

        }catch (\Exception $e) {

            return redirect()->back()->with(['message' => __('words.card-invoice-created'), 'alert-type' => 'success']);
        }

        return redirect()->back()->with(['message' => __('words.card-invoice-created'), 'alert-type' => 'success']);
    }

    public function customerCars(Request $request)
    {

        $rules = [
            'quotation_id' => 'required|integer|exists:quotations,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->errors()->first(), 400);
        }

        try {

            $quotation = Quotation::find($request['quotation_id']);

            $customer = $quotation->customer;

            $cars = $customer->cars()->select('plate_number', 'id')->get();

        } catch (\Exception $e) {

            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return Response::json(['cars' => $cars], 200);
    }

}
