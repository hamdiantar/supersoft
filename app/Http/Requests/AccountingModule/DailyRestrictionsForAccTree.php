<?php

namespace App\Http\Requests\AccountingModule;

use Illuminate\Foundation\Http\FormRequest;

class DailyRestrictionsForAccTree extends FormRequest
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
            'table_data' => 'required',
            'table_data.*.debit_amount' => 'required|numeric',
            'table_data.*.credit_amount' => 'required|numeric'
        ];
    }

    public function attributes() {
        return [
            'table_data.*.debit_amount' => __('accounting-module.debit'),
            'table_data.*.credit_amount' => __('accounting-module.credit'),
        ];
    }
}
