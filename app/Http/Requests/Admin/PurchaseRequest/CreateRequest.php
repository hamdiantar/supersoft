<?php

namespace App\Http\Requests\Admin\PurchaseRequest;

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
        if (request()->has('request_type') && request()->request_type == 'approval') {

            $rules = [
                'status' => 'required|string|in:under_processing,ready_for_approval,accept_approval,reject_approval',
                'items.*.approval_quantity' => 'required|integer|min:1',
                'items.*.item_id' => 'required|integer|exists:purchase_request_items,id',
            ];

        } else {

            $rules = [
                'date' => 'required|date',
                'time' => 'required',
//            'type' => 'required|string|in:negative,positive',
                'description' => 'nullable|string',
                'requesting_party' => 'nullable|string',
                'request_for' => 'nullable|string',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'items' => 'required',
                'items.*.part_id' => 'required|integer|exists:parts,id',
                'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
                'items.*.quantity' => 'required|integer|min:1',
                'status' => 'required|string|in:under_processing,ready_for_approval,accept_approval,reject_approval'
            ];

            if (authIsSuperAdmin()) {
                $rules['branch_id'] = 'required|integer|exists:branches,id';
            }

        }

        return $rules;
    }
}
