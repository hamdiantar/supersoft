<?php


namespace App\Services;


use App\Models\CardInvoiceType;
use App\Models\CardInvoiceTypeItem;
use App\Models\EmployeeData;
use App\Models\Part;
use App\Models\PointRule;
use App\Models\ServicePackage;
use App\Models\Setting;
use App\Models\TaxesFees;
use App\Models\User;
use App\Notifications\CustomerWorkCardStatusNotification;
use App\Notifications\WorkCardStatusNotification;
use Illuminate\Support\Facades\Notification;

trait CardInvoiceSampleServices
{

    public function prepareParts($item)
    {
        $data = [
            'model_id' => $item['id'],
            'qty' => $item['qty'],
            'price' => $item['price'],
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],
        ];

        //       on repeat item save discount one time
//        if ($data['discount_type'] == 'amount' && isset($request['repeat_count']) && $request['repeat_count'] == 1) {
//
//            $data['discount'] = 0;
//        }

        $data['sub_total'] = $data['qty'] * $data['price'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        if ($data['total_after_discount'] < 0) {

            $data['total_after_discount'] = 0;
        }

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareServices($service)
    {

        $data = [
            'model_id' => $service['id'],
            'qty' => $service['qty'],
            'price' => $service['price'],
            'discount_type' => $service['discount_type'],
            'discount' => $service['discount'],
        ];

        $data['sub_total'] = $data['qty'] * $data['price'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        if ($data['total_after_discount'] < 0) {

            $data['total_after_discount'] = 0;
        }

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function preparePackages($item)
    {
        $package = ServicePackage::findOrFail($item['id']);

        $data = [
            'model_id' => $item['id'],
            'qty' => $item['qty'],
            'price' => $package->total_after_discount,
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],
        ];

        $data['sub_total'] = $data['price'] * $data['qty'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        if ($data['total_after_discount'] < 0) {

            $data['total_after_discount'] = 0;
        }

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareWinchRequests($request, $branch_id)
    {
        $setting = Setting::where('branch_id', $branch_id)->first();

        $branch_lat = isset($request['quotation_to_card']) ? $request['branch_lat'] : $setting->lat;
        $branch_long = isset($request['quotation_to_card']) ? $request['branch_long'] : $setting->long;
        $kilo_meter_price = isset($request['quotation_to_card']) ? $request['price'] : $setting->kilo_meter_price;

        $distance = $this->calculateDistanceBetweenTwoAddresses($branch_lat, $branch_long, $request['request_lat'], $request['request_long'], '3959');

        $data = [
            'branch_lat' => $branch_lat,
            'branch_long' => $branch_long,
            'request_lat' => $request['request_lat'],
            'request_long' => $request['request_long'],
            'distance' => $distance,
            'price' => $kilo_meter_price,
            'discount_type' => $request['winch_discount_type'],
            'discount' => $request['winch_discount'],
        ];

        $data['sub_total'] = $data['price'] * $data['distance'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total'] = $data['sub_total'] - $discount;

        if ($data['total'] < 0) {

            $data['total'] = 0;
        }

        return $data;
    }

    public function prepareCardInvoice($request, $branch_id, $work_card)
    {
        $data = [
            'created_by' => auth()->id(),
            'work_card_id' => $work_card->id,
            'time' => $work_card->cardInvoice ? $work_card->cardInvoice->time : $request['time'],
            'date' => $work_card->cardInvoice ? $work_card->cardInvoice->date : $request['date'],
            'type' => $work_card->cardInvoice ? $work_card->cardInvoice->type : $request['type'],
            'terms' => $request['terms'],
            'discount_type' => $request['discount_type'],
            'discount' => $request['discount'],
            'points_discount' => 0,
            'points_rule_id' => null,
        ];

        $data['sub_total'] = 0;
        $customer_discount_value = 0;
        $points_discount = 0;

        if (isset($request['parts'])) {

            foreach ($request['parts'] as $index => $part) {
                $item_data = $this->prepareParts($part);
                $data['sub_total'] += $item_data['total'];
            }
        }

        if (isset($request['services'])) {

            foreach ($request['services'] as $index => $service) {

                $item_data = $this->prepareServices($service);
                $data['sub_total'] += $item_data['total'];
            }
        }

        if (isset($request['packages'])) {

            foreach ($request['packages'] as $index => $package) {

                $item_data = $this->preparePackages($package);
                $data['sub_total'] += $item_data['total'];
            }
        }

        if (isset($request['active_winch_box'])) {

            $item_data = $this->prepareWinchRequests($request, $branch_id);
            $data['sub_total'] += $item_data['total'];
        }

        if (isset($request['customer_discount_check']) && $work_card->customer && $work_card->customer->customerCategory) {

            $customer = $work_card->customer;
            $customer_discount = $customer->customerCategory->services_discount;
            $customer_discount_type = $customer->customerCategory->services_discount_type;

            $customer_discount_value = $this->discountValue($customer_discount_type, $customer_discount, $data['sub_total']);

            $data['customer_discount'] = $customer_discount;
            $data['customer_discount_status'] = 1;
            $data['customer_discount_type'] = $customer_discount_type;
        }

        if ($request['points_rule_id']) {

            $pointRule = PointRule::find($request['points_rule_id']);

            $points_discount += $pointRule ? $pointRule->amount : 0;

            $data['points_discount'] = $points_discount;
            $data['points_rule_id'] = $request['points_rule_id'];
        }

        $data['discount'] = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $total_discount = $data['discount'] + $customer_discount_value;

        $data['total_after_discount'] = $data['sub_total'] - $total_discount;

        if ($data['total_after_discount'] < 0) {

            $data['total_after_discount'] = 0;
        }

        $data['tax'] = isset($request['quotation_to_card']) ? $request['tax'] : $this->taxesValue($data['total_after_discount'], $branch_id);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        $data['discount'] = $request['discount'];

        return $data;
    }

    function discountValue($type, $value, $total)
    {
        if ($type == 'amount') {

            $discount = $value;

        } else {

            $discount = $total * $value / 100;
        }

        return $discount;
    }

    function taxesValue($total, $branch_id)
    {
        $taxes = TaxesFees::where('active_services', 1)->where('branch_id', $branch_id)->get();

        $value = 0;

        foreach ($taxes as $tax) {

            if ($tax->tax_type == 'amount') {

                $value += $tax->value;

            } else {

                $value += round($total * $tax->value / 100, 2);
            }
        }
        return $value;
    }

    public function generateInvoiceNumber($branch_id, $model, $number_column)
    {
        $data = $model::whereHas('workCard', function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
        })->latest('created_at')->first();

        $number = $data ? $data->$number_column + 1 : 1;

        return $number;
    }

    public function updateCardInvoice($work_card)
    {
        if ($work_card->status == 'pending') {

            $work_card->status = 'processing';
            $work_card->save();
        }
    }

    public function createCardInvoiceSampleType($card_invoice_id, $type)
    {
        $card_invoice_type = CardInvoiceType::create([
            'card_invoice_id' => $card_invoice_id,
            'type' => $type,
        ]);

        return $card_invoice_type;
    }

    public function reset($card_invoice)
    {
        foreach ($card_invoice->types as $type) {

            if ($type->items) {

                if ($type->type == 'Part') {
                    $this->resetPurchaseInvoiceQty($type);
                }

                foreach ($type->items as $item) {
                    $item->forceDelete();
                }
            }

            if ($type->cardInvoiceWinchRequest) {

                $type->cardInvoiceWinchRequest->delete();
            }

            $type->forceDelete();
        }

//      RESET CUSTOMER POINTS
        $this->resetInvoicePoints($card_invoice);
    }

    public function resetPurchaseInvoiceQty($type)
    {
        foreach ($type->items as $item) {

            $part = $item->part;

            if ($part) {
                $part->quantity += $item->qty;
                $part->save();
            }

            $purchaseInvoice = $item->purchaseInvoice;

            if ($purchaseInvoice) {

                $purchase_item = $purchaseInvoice->items()->where('part_id', $item->model_id)->first();

                if ($purchase_item) {
                    $purchase_item->purchase_qty += $item->qty;
                    $purchase_item->save();
                }
            }
        }
    }

    public function resetInvoicePoints($invoice)
    {
        $customer = $invoice->workCard->customer;

        if ($customer) {

            foreach ($invoice->pointsLogs as $pointsLog) {

                if ($pointsLog->type == 'additional') {

                    $customer->points -= $pointsLog->points;
                }

                if ($pointsLog->type == 'subtraction') {

                    $customer->points += $pointsLog->points;
                }

                $customer->save();

                $pointsLog->delete();
            }
        }
    }

    public function servicesEmployees($service_item, $employees)
    {

        foreach ($employees as $employee_id) {

            $employeePercent = 0;

            $employee = EmployeeData::findOrFail($employee_id);

            if ($employee->employeeSetting) {

                $employeePercent = $employee->employeeSetting->card_work_percent;
            }

            $total_percent = round($service_item->total_after_discount * $employeePercent / 100, 2);

            $service_item->employees()->attach([$employee_id => ['percent' => $total_percent]]);
        }
    }

    public function packageEmployees($package_item, $employees)
    {
        foreach ($employees as $employee_id) {

            $employeePercent = 0;

            $employee = EmployeeData::findOrFail($employee_id);

            if ($employee->employeeSetting) {
                $employeePercent = $employee->employeeSetting->card_work_percent;
            }

            $total_percent = round($package_item->total_after_discount * $employeePercent / 100, 2);

            $package_item->employees()->attach([$employee_id => ['percent' => $total_percent]]);
        }
    }

    //   when request more quantity of item, more than in purchase invoice selected
    public function repeatPartItem($item, $next_purchase_invoices, $part_invoice_type)
    {
        $requestQty = $item['qty'];

        $item['repeat_count'] = 0;

        foreach ($next_purchase_invoices as $key => $next_purchase_invoice) {

            $item['repeat_count'] = $key;

            $next_invoice_item = $next_purchase_invoice->items()->where('part_id', $item['id'])->first();

            if ($next_invoice_item && $requestQty > 0) {

                $thisInvoiceQty = $next_invoice_item->purchase_qty;

                $qtyGetFromThisInvoice = $thisInvoiceQty >= $requestQty ? $requestQty : $thisInvoiceQty;

                $item['qty'] = $qtyGetFromThisInvoice;

                $item_data = $this->prepareParts($item);

                $item_data['purchase_invoice_id'] = $next_purchase_invoice->id;

                $item_data['card_invoice_type_id'] = $part_invoice_type->id;

                $part_item = CardInvoiceTypeItem::create($item_data);

                $this->affectedPurchaseItem($next_invoice_item, $item['qty']);

                $this->affectedPart($item['id'], $item['qty'], $item['price']);

                $requestQty -= $qtyGetFromThisInvoice;
            }

            if ($requestQty <= 0) {
                break;
            }
        }
    }

    public function affectedPurchaseItem($invoice_item, $sold_qty)
    {
        $invoice_item->purchase_qty -= $sold_qty;
        $invoice_item->save();
    }

    public function affectedPart($part_id, $sold_qty, $selling_price)
    {
        $part = Part::find($part_id);
        $part->quantity -= $sold_qty;
        $part->last_selling_price = $selling_price;
        $part->save();
    }

    public function handlePointsLog($invoice)
    {
        $customer = $invoice->workCard->customer;

        if (!$customer) {

            return false;
        }

        $addLog = __('add points from card invoice');
        $SUBLog = __('subtract points from card invoice');
        $this->addPoints($customer, $addLog, $invoice, 'card_invoice_id');

        if ($invoice->points_rule_id) {

            $pointRule = PointRule::find($invoice->points_rule_id);

            $points = $pointRule ? $pointRule->points : 0;

            $this->subPoints($customer, $SUBLog, $invoice, 'card_invoice_id', $points);
        }
    }

}
