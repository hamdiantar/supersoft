<?php

namespace App\Http\Requests\Admin\SupplyOrders;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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

            'number'=>'required|string|max:50',
            'date' => 'required|date',
            'time' => 'required',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'supplier_discount'=>'required|numeric|min:0',
            'supplier_discount_type'=>'required|string|in:amount,percent',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.part_price_segment_id' => 'nullable|integer|exists:part_price_segments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',
//            'terms.*' => 'nullable|integer|exists:supply_terms,id',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        if (request()->has('type') && request()->type == 'from_purchase_request') {
            $rules['purchase_request_id'] = 'required|integer|exists:purchase_requests,id';
        }

        return $rules;
    }
}
