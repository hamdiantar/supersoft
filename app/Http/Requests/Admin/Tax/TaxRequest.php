<?php

namespace App\Http\Requests\Admin\Tax;

use App\Models\TaxesFees;
use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
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
        $id = request()->segment(5) ?? request()->segment(4);

        $getIgnoredID = TaxesFees::find($id);

        if (authIsSuperAdmin()) {
            $branch = 'required|integer|exists:branches,id';

        } else {
            $branch = '';
        }

        if ($getIgnoredID && $getIgnoredID->id) {

            // Rule::unique('taxes_fees', 'name_ar')->ignore($id) . ',deleted_at,NULL'

            $ruleAR = ['required', 'string', 'max:50' ];

            // Rule::unique('taxes_fees', 'name_en')->ignore($id) . ',deleted_at,NULL'

            $ruleEN = ['required', 'string', 'max:50' ];

        } else {

            // unique:taxes_fees,name_ar,NULL,id,deleted_at,NULL

            $ruleAR = 'required|string|max:50';
            $ruleEN = 'required|string|max:50';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'value' => 'required_if:type,tax|numeric',
            'tax_type' => 'required|in:amount,percentage',
            'branch_id' => $branch,
            'type' => 'required|string|in:tax,additional_payments',
            'execution_time' => 'required|string|in:after_discount,before_discount'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('Name'),
            'value' => __('Tax /Fee Value'),
            'tax_type' => __('Tax /Fee Type'),
            'branch_id' => __('Branch')
        ];
    }

    public function messages()
    {
       return [
           'value.required_if' => __('The amount of tax / fee is required if the type is equal to tax'),
       ];
    }
}
