<?php

namespace App\Http\Requests\Admin\PurchaseInvoiceExecution;

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
        return [
            'date_from'=>'required|date',
            'date_to'=>'required|date|after_or_equal:date_from',
            'status' => 'required|string|in:pending,late,finished',
            'notes' => 'nullable|string',
            'item_id'=>'required|integer|exists:purchase_invoices,id'
        ];
    }
}
