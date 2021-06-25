<?php

namespace App\Http\Requests\Admin\CardInvoiceSample;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCardInvoiceRequest extends FormRequest
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

            'terms'=>'nullable|string',
            'discount'=>'required|numeric|min:0',
            'discount_type'=>'required|string|in:amount,percent',
        ];

        if (request()->has('parts')) {

            $rules['parts'] = 'required';
            $rules['parts.*.id'] = 'required|integer|exists:parts,id';
            $rules['parts.*.qty'] = 'required|integer|min:1';
            $rules['parts.*.purchase_invoice_id'] = 'nullable|integer|exists:purchase_invoices,id';
            $rules['parts.*.price'] = 'required|numeric|min:0';
            $rules['parts.*.discount_type'] = 'required|string|in:amount,percent';
            $rules['parts.*.discount'] = 'required|numeric|min:0';
        }

        if (request()->has('services')) {

            $rules['services'] = 'required';
            $rules['services.*.id'] = 'required|integer|exists:services,id';
            $rules['services.*.qty'] = 'required|integer|min:1';
            $rules['services.*.price'] = 'required|numeric|min:0';
            $rules['services.*.discount_type'] = 'required|string|in:amount,percent';
            $rules['services.*.discount'] = 'required|numeric|min:0';
        }

        if (request()->has('packages')) {

            $rules['packages'] = 'required';
            $rules['packages.*.id'] = 'required|integer|exists:service_packages,id';
            $rules['packages.*.qty'] = 'required|integer|min:1';
            $rules['packages.*.price'] = 'required|numeric|min:0';
            $rules['packages.*.discount_type'] = 'required|string|in:amount,percent';
            $rules['packages.*.discount'] = 'required|numeric|min:0';
        }

        if (request()->has('active_winch_box')) {

            $rules['request_long'] = 'required|numeric';
            $rules['request_lat'] = 'required|numeric';
            $rules['winch_discount_type'] = 'required|string|in:amount,percent';
            $rules['winch_discount'] = 'required|numeric|min:0';
        }

        return $rules;
    }
}
