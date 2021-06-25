<?php

namespace App\Http\Requests\Admin\PurchaseInvoice;

use App\Models\PurchaseInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [

            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'time' => 'required',
            'date' => 'required|date',
//            'items'=> 'required',
            'items.*.id'=> 'required|integer|exists:parts,id',
            'items.*.purchase_qty'=> 'required|integer|min:1',
            'items.*.last_purchase_price'=> 'required|numeric|min:0',
            'items.*.purchase_price'=> 'required|numeric|min:0',
            'items.*.store_id'=> 'required|integer|exists:stores,id',
            'items.*.discount_type'=> 'required|string|in:amount,percent',
            'items.*.discount'=> 'required|numeric|min:0',
            'items.*.part_price_id'=> 'required|integer|exists:part_prices,id',


            'discount_type'=>'required|string|in:amount,percent',
            'discount'=>'required|numeric|min:0',
            'taxes.*'=>'nullable|integer|exists:taxes_fees,id',
        ];

        $id = request()->segment(5);

        $invoice = PurchaseInvoice::find($id);

        if ($invoice) {
            $validationForInvoiceN = ['required',
                Rule::unique('purchase_invoices', 'invoice_number')->ignore($invoice)
                    ->where('deleted_at', null)];
        } else {
            $validationForInvoiceN = "required|unique:purchase_invoices,invoice_number,NULL,id,deleted_at,NULL";
        }

        $rules['invoice_number'] = $validationForInvoiceN;

        if(authIsSuperAdmin()){

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        return $rules;
    }

    public function attributes()
    {
       return [
         'invoice_number' => __('Invoice Number')
       ];
    }
}
