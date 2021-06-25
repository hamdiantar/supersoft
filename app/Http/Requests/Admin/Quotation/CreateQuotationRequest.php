<?php

namespace App\Http\Requests\Admin\Quotation;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'time' => 'required',
            'invoice_tax' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'discount' => 'required|numeric|min:0',
        ];

        if(request()->has('parts_box')){

            $rules['part_ids'] = 'required';
            $rules['part_ids.*'] = 'required|integer|exists:parts,id';

//            $rules['purchase_invoice_id'] = 'required';
//            $rules['purchase_invoice_id.*'] = 'required|integer|exists:purchase_invoices,id';

            $rules['sold_qty'] = 'required';
            $rules['sold_qty.*'] = 'required|integer|min:1';

            $rules['selling_price'] = 'required';
            $rules['selling_price.*'] = 'required|numeric|min:1';

            if($this->request->has('part_ids')) {

                foreach (request()['part_ids'] as $index=>$part_id) {

                    $part_index = request()['index'][$index];
                    $rules['item_discount_type_' . $part_index] = 'required|string|in:amount,percent';
                }


            }

            $rules['item_discount'] = 'required';
            $rules['item_discount.*'] = 'required|numeric|min:0';
        }

        if(request()->has('services_box')){

            $rules['service_ids'] = 'required';
            $rules['service_ids.*'] = 'required|integer|exists:services,id';

            $rules['services_qty'] = 'required';
            $rules['services_qty.*'] = 'required|integer|min:1';

            $rules['services_prices'] = 'required';
            $rules['services_prices.*'] = 'required|numeric|min:1';

            foreach (request()['service_ids'] as $index => $service_id) {
                $rules['service_discount_type_'.$service_id] = 'required|string|in:amount,percent';
//                $rules['service_discount_type'.$service_id.'.*'] = 'required|string|in:amount,percent';
            }

            $rules['services_discounts'] = 'required';
            $rules['services_discounts.*'] = 'required|numeric|min:0';
        }

        if(request()->has('packages_box')){

            $rules['package_ids'] = 'required';
            $rules['package_ids.*'] = 'required|integer|exists:service_packages,id';

            $rules['packages_qty'] = 'required';
            $rules['packages_qty.*'] = 'required|integer|min:0';

            foreach (request()['package_ids'] as $index => $package_id) {
                $rules['package_discount_type_'.$package_id] = 'required|string|in:amount,percent';
//                $rules['package_discount_type_'.$package_id.'.*'] = 'required|string|in:amount,percent';
            }

            $rules['packages_discounts'] = 'required';
            $rules['packages_discounts.*'] = 'required|numeric|min:0';
        }

        if (request()->has('winch_box')) {

            $rules['request_long'] = 'required|numeric';
            $rules['request_lat'] = 'required|numeric';
            $rules['winch_discount_type'] = 'required|string|in:amount,percent';
            $rules['winch_discount'] = 'required|numeric|min:0';
        }

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
