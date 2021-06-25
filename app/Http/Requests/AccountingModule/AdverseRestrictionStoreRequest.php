<?php

namespace App\Http\Requests\AccountingModule;

use Illuminate\Foundation\Http\FormRequest;

class AdverseRestrictionStoreRequest extends FormRequest
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
            'operation_number' => 'required|numeric',
            'operation_date' => 'required|date',
            'table_data.*.accounts_tree_id' => 'required',
            'table_data.*.debit_amount' => 'required|numeric',
            'table_data.*.credit_amount' => 'required|numeric',
            'table.*.notes' => 'nullable',
            'credit_amount' => 'required|same:debit_amount',
            'debit_amount' => 'required|same:credit_amount',
        ];
    }

    public function attributes() {
        return [
            'operation_number' => __('accounting-module.operation-number'),
            'operation_date' => __('accounting-module.operation-date'),
            'table_data.*.accounts_tree_id' => __('accounting-module.account-name'),
            'table_data.*.debit_amount' => __('accounting-module.debit'),
            'table_data.*.credit_amount' => __('accounting-module.credit'),
            'credit_amount' => __('accounting-module.credit-amount'),
            'debit_amount' => __('accounting-module.debit-amount')
        ];
    }
}
