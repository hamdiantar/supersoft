<?php

namespace App\Http\Requests\Admin\PurchaseReceipt;

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
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
            'supply_order_id' => 'required|integer|exists:supply_orders,id',

            'items.*.store_id' => 'required|integer|exists:stores,id',
            'items.*.refused_quantity' => 'required|integer|min:0',
            'items.*.accepted_quantity' => 'required|integer|min:0',
            'items.*.supply_order_item_id' => 'required|integer|exists:supply_order_items,id',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
