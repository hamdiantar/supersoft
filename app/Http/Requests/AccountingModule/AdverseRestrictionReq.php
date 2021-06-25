<?php
namespace App\Http\Requests\AccountingModule;

use Illuminate\Foundation\Http\FormRequest;

class AdverseRestrictionReq extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'fiscal_year' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'acc_tree_id' => 'required',
            'date' => 'required|date'
        ];
    }

    public function attributes() {
        return [
            'fiscal_year' => __('accounting-module.fiscal-years-index'),
            'date_from' => __('accounting-module.date-from'),
            'date_to' => __('accounting-module.date-to'),
            'acc_tree_id' => __('accounting-module.account-branch'),
            'date' =>  __('accounting-module.adverse-restriction-date'),
        ];
    }
}
