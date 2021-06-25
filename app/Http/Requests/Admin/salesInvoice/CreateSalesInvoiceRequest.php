<?php

namespace App\Http\Requests\Admin\salesInvoice;

use Illuminate\Foundation\Http\FormRequest;

class CreateSalesInvoiceRequest extends FormRequest
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

            'customer_id' => 'nullable|integer|exists:customers,id',
            'date' => 'required|date',
            'time' => 'required',
            'part_ids' => 'required',
            'part_ids.*' => 'required|integer|exists:parts,id',

//            'purchase_invoice_id' => 'required',
//            'purchase_invoice_id.*' => 'required|integer|exists:purchase_invoices,id',

            'available_qy' => 'required',
            'available_qy.*' => 'required|integer|min:1',

            'sold_qty' => 'required',
            'sold_qty.*' => 'required|integer|min:1',

            'last_selling_price' => 'required',
            'last_selling_price.*' => 'required|numeric|min:0',

            'selling_price' => 'required',
            'selling_price.*' => 'required|numeric|min:1',

            'item_discount' => 'nullable',
            'item_discount.*' => 'nullable|numeric|min:0',

            'parts_count' => 'required|integer|min:1',
            'invoice_tax' => 'required|numeric|min:0',

            'discount_type' => 'required|string|in:amount,percent',
            'discount' => 'nullable|numeric|min:0',
            'type' => 'required|string|in:cash,credit'
        ];

        if($this->request->has('part_ids')) {
            foreach (request()['part_ids'] as $index => $part_id) {

                $part_index = request()['index'][$index];

                $rules['item_discount_type_' . $part_index] = 'required|string|in:amount,percent';
            }
        }

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
