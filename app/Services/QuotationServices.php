<?php

namespace App\Services;

use App\Models\QuotationType;
use App\Models\QuotationTypeItem;
use App\Models\ServicePackage;
use App\Models\Setting;
use App\Models\TaxesFees;

trait QuotationServices
{
    public function prepareParts($request, $index, $part_id)
    {

        $part_index = request()['index'][$index];

        $data = [
            'model_id' => $request['part_ids'][$index],
            'qty' => $request['sold_qty'][$index],
            'price' => $request['selling_price'][$index],
            'discount_type' => $request['item_discount_type_' . $part_index],
            'discount' => $request['item_discount'][$index],
        ];

        //       on repeat item save discount one time
        if ($data['discount_type'] == 'amount' && isset($request['repeat_count']) && $request['repeat_count'] == 1) {

            $data['discount'] = 0;
        }

        $data['sub_total'] = $request['sold_qty'][$index] * $request['selling_price'][$index];

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareServices($request, $index, $service_id)
    {

        $data = [
            'model_id' => $request['service_ids'][$index],
            'qty' => $request['services_qty'][$index],
            'price' => $request['services_prices'][$index],
            'discount_type' => $request['service_discount_type_' . $service_id],
            'discount' => $request['services_discounts'][$index],
        ];

        $data['sub_total'] = $request['services_qty'][$index] * $request['services_prices'][$index];

        $discount = $this->discountValue($data['discount_type'], $request['services_discounts'][$index], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function preparePackages($request, $index, $package_id)
    {

        $package = ServicePackage::findOrFail($request['package_ids'][$index]);

        $data = [
            'model_id' => $request['package_ids'][$index],
            'qty' => $request['packages_qty'][$index],
            'price' => $package->total_after_discount,
            'discount_type' => $request['package_discount_type_' . $package_id],
            'discount' => $request['packages_discounts'][$index],
        ];

        $data['sub_total'] = $data['price'] * $data['qty'];

        $discount = $this->discountValue($data['discount_type'], $request['packages_discounts'][$index], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount;

        $data['total'] = $data['total_after_discount'];

        return $data;
    }

    public function prepareWinchRequests($request, $setting)
    {
        $branch_lat  = $setting->lat;
        $branch_long = $setting->long;
        $kilo_meter_price = $setting->kilo_meter_price;

        $distance = $this->calculateDistanceBetweenTwoAddresses($branch_lat,$branch_long, $request['request_lat'],$request['request_long'],'3959');

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

    public function prepareQuotationData($request, $branch_id, $setting)
    {
        $data = [

            'customer_id' => $request['customer_id'],
            'time' => $request['time'],
            'date' => $request['date'],
            'discount_type' => $request['discount_type'],
            'discount' => $request['discount'],
        ];

        $data['sub_total'] = 0;

        if (isset($request['parts_box'])) {

            foreach ($request['part_ids'] as $index => $part_id) {
                $item_data = $this->prepareParts($request, $index, $part_id);
                $data['sub_total'] += $item_data['total'];
            }
        }

        if (isset($request['services_box'])) {

            foreach ($request['service_ids'] as $index => $service_id) {

                $item_data = $this->prepareServices($request, $index, $service_id);
                $data['sub_total'] += $item_data['total'];
            }
        }

        if (isset($request['packages_box'])) {

            foreach ($request['package_ids'] as $index => $package_id) {

                $item_data = $this->preparePackages($request, $index, $package_id);
                $data['sub_total'] += $item_data['total'];
            }
        }

        if (isset($request['winch_box'])) {

            $winch_data = $this->prepareWinchRequests($request, $setting);
            $data['sub_total'] += $winch_data['total'];
        }

        $data['discount'] = $this->discountValue($request['discount_type'], $request['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $data['discount'];

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
        $taxes = TaxesFees::where('active_offers', 1)->where('branch_id', $branch_id)->get();

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

    public function validationRules($request)
    {
        $rules = [

            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'time' => 'required',
            'invoice_tax' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'discount' => 'required|numeric|min:0',
        ];

        if ($request->has('parts_box')) {

            $rules['part_ids'] = 'required';
            $rules['part_ids.*'] = 'required|integer|exists:parts,id';

            $rules['purchase_invoice_id'] = 'required';
            $rules['purchase_invoice_id.*'] = 'required|integer|exists:purchase_invoices,id';

            $rules['sold_qty'] = 'required';
            $rules['sold_qty.*'] = 'required|integer|min:1';

            $rules['selling_price'] = 'required';
            $rules['selling_price.*'] = 'required|numeric|min:1';

            $rules['item_discount_type'] = 'required';
            $rules['item_discount_type.*'] = 'required|string|in:amount,percent';

            $rules['item_discount'] = 'required';
            $rules['item_discount.*'] = 'required|numeric|min:0';
        }

        if ($request->has('services_box')) {

            $rules['service_ids'] = 'required';
            $rules['service_ids.*'] = 'required|integer|exists:services,id';

            $rules['services_qty'] = 'required';
            $rules['services_qty.*'] = 'required|integer|min:1';

            $rules['services_prices'] = 'required';
            $rules['services_prices.*'] = 'required|numeric|min:1';

            $rules['services_discounts_types'] = 'required';
            $rules['services_discounts_types.*'] = 'required|string|in:amount,percent';

            $rules['services_discounts'] = 'required';
            $rules['services_discounts.*'] = 'required|numeric|min:0';
        }

        if ($request->has('packages_box')) {

            $rules['package_ids'] = 'required';
            $rules['package_ids.*'] = 'required|integer|exists:service_packages,id';

            $rules['packages_discounts_types'] = 'required';
            $rules['packages_discounts_types.*'] = 'required|string|in:amount,percent';

            $rules['packages_discounts'] = 'required';
            $rules['packages_discounts.*'] = 'required|numeric|min:0';
        }

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }

    public function createQuotationType($quotation_id, $type)
    {

        $quotation_type = QuotationType::create([
            'quotation_id' => $quotation_id,
            'type' => $type,
        ]);

        return $quotation_type;
    }

    public function reset($quotation, $action_type)
    {

        foreach ($quotation->types as $type) {

            foreach ($type->items as $item) {

                if ($action_type == 'delete') {
                    $item->delete();
                } else {
                    $item->forceDelete();
                }
            }

            if ($type->winchRequest) {

                $type->winchRequest->delete();
            }

            if ($action_type == 'delete') {
                $type->delete();
            } else {
                $type->forceDelete();
            }

        }
    }

    //   when request more quantity of item, more than in purchase invoice selected
    public function repeatPartItem($data, $next_purchase_invoices, $index, $part_id, $quotation, $quotation_type)
    {

        $requestQty = $data['sold_qty'][$index];

        $data['repeat_count'] = 0;

        foreach ($next_purchase_invoices as $key => $next_purchase_invoice) {

            $data['repeat_count'] = $key;

            $next_invoice_item = $next_purchase_invoice->items()->where('part_id', $part_id)->first();

            if ($next_invoice_item && $requestQty > 0) {

                $thisInvoiceQty = $next_invoice_item->purchase_qty;

                $qtyGetFromThisInvoice = $thisInvoiceQty >= $requestQty ? $requestQty : $thisInvoiceQty;

                $data['sold_qty'][$index] = $qtyGetFromThisInvoice;

                $item_data = $this->prepareParts($data, $index, $part_id);

                $item_data['purchase_invoice_id'] = $next_purchase_invoice->id;
                $item_data['quotation_id'] = $quotation->id;
                $item_data['quotation_type_id'] = $quotation_type->id;

                $part_item = QuotationTypeItem::create($item_data);

                $requestQty -= $qtyGetFromThisInvoice;
            }

            if ($requestQty <= 0) {
                break;
            }
        }
    }

    function calculateDistanceBetweenTwoAddresses($lat1, $lng1, $lat2, $lng2, $type){

        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);

        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $delta_lat = $lat2 - $lat1;
        $delta_lng = $lng2 - $lng1;

        $hav_lat = (sin($delta_lat / 2))**2;
        $hav_lng = (sin($delta_lng / 2))**2;

        $distance = 2 * asin(sqrt($hav_lat + cos($lat1) * cos($lat2) * $hav_lng));

        $distance = round($type*$distance,1);
        // If you want calculate the distance in miles instead of kilometers, replace 6371 with 3959.

        return $distance;
    }
}
