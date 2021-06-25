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

trait CardInvoiceServices
{
    public function prepareParts($request, $maintenance_detection_id, $part_id, $index)
    {
        $part_index = $request['part_index_'.$maintenance_detection_id][$index];

        $requestName = 'part_' . $part_id . '_maintenance_' . $maintenance_detection_id . '_index_'. $part_index ;

        $data = [
//            'purchase_invoice_id' => $request[$requestName]['purchase_invoice_id'],
            'model_id' => $part_id,
            'qty' => $request[$requestName]['qty'],
            'price' => $request[$requestName]['price'],
            'discount_type' => $request[$requestName]['discount_type'],
            'discount' => $request[$requestName]['discount'],
        ];

        //       on repeat item save discount one time
        if ($data['discount_type'] == 'amount' && isset($request['repeat_count']) && $request['repeat_count'] == 1) {

            $data['discount'] = 0;
        }

        $data['sub_total'] = $data['qty'] * $data['price'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareServices($request, $maintenance_detection_id, $service_id)
    {

        $requestName = 'service_' . $service_id . '_maintenance_' . $maintenance_detection_id;

        $data = [
            'model_id' => $service_id,
            'qty' => $request[$requestName]['qty'],
            'price' => $request[$requestName]['price'],
            'discount_type' => $request[$requestName]['discount_type'],
            'discount' => $request[$requestName]['discount'],
        ];

        $data['sub_total'] = $data['qty'] * $data['price'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function preparePackages($request, $maintenance_detection_id, $package_id)
    {

        $requestName = 'package_' . $package_id . '_maintenance_' . $maintenance_detection_id;
        $package = ServicePackage::findOrFail($package_id);

        $data = [
            'model_id' => $package_id,
            'qty' => $request[$requestName]['qty'],
            'price' => $package->total_after_discount,
            'discount_type' => $request[$requestName]['discount_type'],
            'discount' => $request[$requestName]['discount'],
        ];

        $data['sub_total'] = $data['price'] * $data['qty'];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareWinchRequests($request, $branch_id)
    {
        $setting = Setting::where('branch_id', $branch_id)->first();

        $branch_lat  = $setting->lat;
        $branch_long = $setting->long;
        $kilo_meter_price = $setting->kilo_meter_price;

        $distance = $this->calculateDistanceBetweenTwoAddresses($branch_lat, $branch_long, $request['request_lat'],$request['request_long'],'3959');

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

        return $data;
    }

    public function prepareCardInvoiceData($request, $branch_id, $work_card)
    {
        $data = [

            'time' => $request['time'],
            'date' => $request['date'],
            'type' => $request['type'],
            'terms' => $request['terms'],
            'discount_type' => $request['discount_type'],
            'discount' => $request['discount'],
            'customer_discount_status' => 0,
            'customer_discount' => 0,
            'points_discount' => 0,
            'points_rule_id' => null,
        ];

        $data['sub_total'] = 0;
        $data['customer_discount'] = 0;
        $maintenance_type_discount = 0;
        $customer_discount_value = 0;
        $points_discount = 0;

        foreach ($request['maintenance_types'] as $maintenance_type) {

            $maintenance_type_request = 'maintenance_type_' . $maintenance_type;

            $maintenance_type_discount += $this->discountValue($request[$maintenance_type_request]['discount_type'],
                $request[$maintenance_type_request]['discount'], $request[$maintenance_type_request]['sub_total']);

            foreach ($request['type-' . $maintenance_type]['maintenance_type_parts'] as $maintenance_type_part) {

                if (isset($request['part_ids_' . $maintenance_type_part])) {

                    foreach ($request['part_ids_' . $maintenance_type_part] as $index => $part_id) {
                        $item_data = $this->prepareParts($request, $maintenance_type_part, $part_id, $index);
                        $data['sub_total'] += $item_data['total'];
                    }
                }

                if (isset($request['service_ids_' . $maintenance_type_part])) {

                    foreach ($request['service_ids_' . $maintenance_type_part] as $index => $service_id) {

                        $item_data = $this->prepareServices($request, $maintenance_type_part, $service_id);
                        $data['sub_total'] += $item_data['total'];
                    }
                }

                if (isset($request['package_ids_' . $maintenance_type_part])) {

                    foreach ($request['package_ids_' . $maintenance_type_part] as $index => $package_id) {

                        $item_data = $this->preparePackages($request, $maintenance_type_part, $package_id);
                        $data['sub_total'] += $item_data['total'];
                    }
                }
            }
        }

        if (isset($request['active_winch_box'])) {

            $item_data = $this->prepareWinchRequests($request, $branch_id);
            $data['sub_total'] += $item_data['total'];
        }

        if (isset($request['customer_discount_check']) && $work_card->customer->customerCategory) {

            $customer = $work_card->customer;
            $customer_discount = $customer->customerCategory->services_discount;
            $customer_discount_type = $customer->customerCategory->services_discount_type;

            $customer_discount_value =  $this->discountValue($customer_discount_type, $customer_discount, $data['sub_total']);

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

        $data['discount'] = $this->discountValue($request['discount_type'], $request['discount'], $data['sub_total']);

        $data['sub_total'] -= $maintenance_type_discount;

        $total_discount = ($data['discount'] + $customer_discount_value + $data['points_discount']);

        $data['total_after_discount'] = $data['sub_total'] - $total_discount;

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $branch_id);

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

    public function createCardInvoiceType($card_invoice_id, $maintenance_detection_id, $type)
    {

        $card_invoice_type = CardInvoiceType::create([
            'card_invoice_id' => $card_invoice_id,
            'maintenance_detection_id' => $maintenance_detection_id,
            'type' => $type,
        ]);

        return $card_invoice_type;
    }

    public function rules($request)
    {

        $rules = [
            'work_card_id' => 'required|integer|exists:work_cards,id',
            'date' => 'required|date',
            'time' => 'required',
            'type' => 'required|in:cash,credit',
            'terms' => 'nullable',
        ];

        $rules['maintenance_types'] = 'required';
        $rules['maintenance_types.*'] = 'required|integer|exists:maintenance_detection_types,id';


        if ($request->has('maintenance_types')) {

            foreach ($request['maintenance_types'] as $type) {
                $rules['type-' . $type] = 'required';

                if ($request->has('type-' . $type)) {
                    foreach ($request['type-' . $type]['maintenance_type_parts'] as $maintenance_type_part) {
                        $rules['notes_' . $maintenance_type_part] = 'nullable';
                        $rules['degree_' . $maintenance_type_part] = 'required|integer|in:1,2,3';
                        $rules['image_' . $maintenance_type_part] = 'nullable';
                        $rules['image_' . $maintenance_type_part . '.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
                    }
                }
            }
        }

        return $rules;
    }

    public function rulesInUpdate($request)
    {
        $rules = [
            'work_card_id' => 'required|integer|exists:work_cards,id',
            'date' => 'required|date',
            'time' => 'required',
            'type' => 'required|in:cash,credit',
            'terms' => 'nullable',
        ];

        $rules['maintenance_types'] = 'required';
        $rules['maintenance_types.*'] = 'required|integer|exists:maintenance_detection_types,id';

        if ($request->has('maintenance_types')) {

            foreach ($request['maintenance_types'] as $type) {
                $rules['type-' . $type] = 'required';

                $rules['maintenance_type_' . $type . '.sub_total'] = 'required|numeric|min:0';
                $rules['maintenance_type_' . $type . '.discount_type'] = 'required|string|in:amount,percent';
                $rules['maintenance_type_' . $type . '.discount'] = 'required|numeric|min:0';
                $rules['maintenance_type_' . $type . '.total_after_discount'] = 'required|numeric|min:0';

                if ($request->has('type-' . $type)) {

                    foreach ($request['type-' . $type]['maintenance_type_parts'] as $maintenance_type_part) {
                        $rules['notes_' . $maintenance_type_part] = 'nullable';
                        $rules['degree_' . $maintenance_type_part] = 'required|integer|in:1,2,3';
                        $rules['image_' . $maintenance_type_part] = 'nullable';
                        $rules['image_' . $maintenance_type_part . '.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';

                        if ($request->has('service_ids_' . $maintenance_type_part)) {

                            $services_rules = $this->servicesRules($request, $maintenance_type_part);
                            $rules = (array_merge($rules, $services_rules));
                        }
                        if ($request->has('package_ids_' . $maintenance_type_part)) {

                            $package_rules = $this->packagesRules($request, $maintenance_type_part);
                            $rules = (array_merge($rules, $package_rules));
                        }
                        if ($request->has('part_ids_' . $maintenance_type_part)) {

                            $part_rules = $this->partsRules($request, $maintenance_type_part);
                            $rules = (array_merge($rules, $part_rules));
                        }

                    }

                }
            }
        }

        return $rules;
    }

    public function servicesRules($request, $maintenance_type_part)
    {

        $rules = [];

        foreach ($request['service_ids_' . $maintenance_type_part] as $service_id) {

            $service_qty = 'service_' . $service_id . '_maintenance_' . $maintenance_type_part . '.qty';
            $rules[$service_qty] = 'required|integer|min:0';

            $service_price = 'service_' . $service_id . '_maintenance_' . $maintenance_type_part . '.price';
            $rules[$service_price] = 'required|numeric|min:0';

            $service_discount_type = 'service_' . $service_id . '_maintenance_' . $maintenance_type_part . '.discount_type';
            $rules[$service_discount_type] = 'required|string|in:amount,percent';

            $service_discount = 'service_' . $service_id . '_maintenance_' . $maintenance_type_part . '.discount';
            $rules[$service_discount] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function packagesRules($request, $maintenance_type_part)
    {

        $rules = [];

        foreach ($request['package_ids_' . $maintenance_type_part] as $package_id) {

            $package_qty = 'package_' . $package_id . '_maintenance_' . $maintenance_type_part . '.qty';
            $rules[$package_qty] = 'required|integer|min:0';

            $package_price = 'package_' . $package_id . '_maintenance_' . $maintenance_type_part . '.price';
            $rules[$package_price] = 'required|numeric|min:0';

            $package_discount_type = 'package_' . $package_id . '_maintenance_' . $maintenance_type_part . '.discount_type';
            $rules[$package_discount_type] = 'required|string|in:amount,percent';

            $package_discount = 'package_' . $package_id . '_maintenance_' . $maintenance_type_part . '.discount';
            $rules[$package_discount] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function partsRules($request, $maintenance_type_part)
    {

        $rules = [];

        foreach ($request['part_ids_' . $maintenance_type_part] as $index=>$part_id) {

            $partIndex = request()['part_index_'.$maintenance_type_part][$index];

            $requestName = 'part_' . $part_id . '_maintenance_' . $maintenance_type_part .'_index_' . $partIndex;

            $part_purchase_invoice_id = $requestName.'.purchase_invoice_id';
            $rules[$part_purchase_invoice_id] = 'nullable|integer|exists:purchase_invoices,id';

            $part_qty = $requestName . '.qty';
            $rules[$part_qty] = 'required|integer|min:0';

            $part_price = $requestName. '.price';
            $rules[$part_price] = 'required|numeric|min:0';

            $part_discount_type = $requestName . '.discount_type';
            $rules[$part_discount_type] = 'required|string|in:amount,percent';

            $part_discount = $requestName . '.discount';
            $rules[$part_discount] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function generateInvoiceNumber($branch_id, $model, $number_column)
    {
        $data = $model::whereHas('workCard', function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id)->latest('created_at');
        })->latest('created_at')->first();

        $number = $data ? $data->$number_column + 1 : 1;

        return $number;
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

            $this->resetInvoicePoints($card_invoice);
        }
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

    public function servicesEmployees($request, $service_item, $service_id, $maintenance_type_part)
    {

        $serviceRequestName = 'service_' . $service_id . '_maintenance_' . $maintenance_type_part;

        if (isset($request[$serviceRequestName]['employees'])) {

            foreach ($request[$serviceRequestName]['employees'] as $employee_id) {

                $employeePercent = 0;

                $employee = EmployeeData::findOrFail($employee_id);

                if ($employee->employeeSetting) {
                    $employeePercent = $employee->employeeSetting->card_work_percent;
                }

                $total_percent = round($service_item->total_after_discount * $employeePercent / 100, 2);

                $service_item->employees()->attach([$employee_id => ['percent' => $total_percent]]);
            }
        }
    }

    public function packageEmployees($request, $package_item, $package_id, $maintenance_type_part)
    {

        $packageRequestName = 'package_' . $package_id . '_maintenance_' . $maintenance_type_part;

        if (isset($request[$packageRequestName]['employees'])) {

            foreach ($request[$packageRequestName]['employees'] as $employee_id) {

                $employeePercent = 0;

                $employee = EmployeeData::findOrFail($employee_id);

                if ($employee->employeeSetting) {
                    $employeePercent = $employee->employeeSetting->card_work_percent;
                }

                $total_percent = round($package_item->total_after_discount * $employeePercent / 100, 2);

                $package_item->employees()->attach([$employee_id => ['percent' => $total_percent]]);
            }
        }
    }

    //   when request more quantity of item, more than in purchase invoice selected
    public function repeatPartItem($request, $next_purchase_invoices, $maintenance_type_part, $part_id, $part_invoice_type, $index)
    {
        $part_index = $request['part_index_'.$maintenance_type_part][$index];

        $requestName = 'part_' . $part_id . '_maintenance_' . $maintenance_type_part . '_index_'. $part_index;

        $requestQty = $request[$requestName]['qty'];

        $request['repeat_count'] = 0;

        foreach ($next_purchase_invoices as $key => $next_purchase_invoice) {

            $request['repeat_count'] = $key;

            $next_invoice_item = $next_purchase_invoice->items()->where('part_id', $part_id)->first();

            if ($next_invoice_item && $requestQty > 0) {

                $thisInvoiceQty = $next_invoice_item->purchase_qty;

                $qtyGetFromThisInvoice = $thisInvoiceQty >= $requestQty ? $requestQty : $thisInvoiceQty;

                $request[$requestName]['qty'] = $qtyGetFromThisInvoice;

                $item_data = $this->prepareParts($request, $maintenance_type_part, $part_id, $index);

                $item_data['purchase_invoice_id'] = $next_purchase_invoice->id;

                $item_data['card_invoice_type_id'] = $part_invoice_type->id;

                $part_item = CardInvoiceTypeItem::create($item_data);

                $this->affectedPurchaseItem($next_invoice_item, $request[$requestName]['qty']);

                $this->affectedPart($part_id, $request[$requestName]['qty'], $request[$requestName]['price']);

                $requestQty -= $qtyGetFromThisInvoice;
            }

            if ($requestQty <= 0) {
                break;
            }
        }
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
}
