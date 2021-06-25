<?php

namespace App\Http\Requests\Admin\SalesInvoicesReturn;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesInvoiceReturnRequest extends FormRequest
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
//            'type'=>'required|string|in:cash,credit',
            'time'=>'required',
            'date'=>'required|date',
            'number_of_items'=>'required|integer|min:1',
            'sales_invoice_id'=>'required|integer|exists:sales_invoices,id',
            'discount_type'=>'required|string|in:percent,amount',
            'discount'=>'required|numeric|min:0',
            'return_part_ids'=>'nullable',
            'return_part_ids.*'=>'nullable|integer|exists:parts,id',
        ];

        if(request()->has('return_part_ids')){

            foreach (request()['return_part_ids'] as $index => $part_id){


                $rules['sales_invoice_items_id_'.$index] = 'required|integer|exists:sales_invoice_items,id';
//                $rules['purchase_invoice_id_'.$part_id] = 'required|integer|exists:purchase_invoices,id';
                $rules['return_qty_'.$index] = 'required|integer|min:1';
                $rules['last_selling_price_'.$index] = 'required|numeric|min:0';
                $rules['selling_price_'.$index] = 'required|numeric|min:0';
                $rules['item_discount_type_'.$index] = 'required|string|in:percent,amount';
                $rules['item_discount_'.$index] = 'required|numeric|min:0';
            }
        }

        return $rules;
    }
}
