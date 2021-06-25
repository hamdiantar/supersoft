<?php

namespace App\Http\Requests\Admin\PurchaseReturn;

use App\Model\PurchaseReturn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseReturnRequest extends FormRequest
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
        $id = request()->segment(5);
        $invoice = PurchaseReturn::find($id);
        if ($invoice) {
            $validationForInvoiceN = ['required',
                Rule::unique('purchase_returns', 'invoice_number')->ignore($invoice)
                    ->where('deleted_at', null)];
        } else {
            $validationForInvoiceN = "required|unique:purchase_returns,invoice_number,NULL,id,deleted_at,NULL";
        }

        $rules = [
            'purchase_invoice_id' => 'required|exists:purchase_invoices,id',
            'invoice_number' => $validationForInvoiceN,
            'date' => 'required',
            'time' => 'required',
            'number_of_items' => 'required',
            'type' => 'required|in:cash,credit',
            'discount_group_type' => 'required|string|in:percent,amount',
            'discount_group_value' => 'required|numeric|min:0',
        ];

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
