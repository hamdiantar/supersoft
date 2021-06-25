<?php

namespace App\Http\Requests\AccountingModule;

use Illuminate\Foundation\Http\FormRequest;

class FiscalYearReq extends FormRequest
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('accounting-module.name-ar'),
            'name_en' => __('accounting-module.name-en'),
            'start_date' => __('accounting-module.start-date'),
            'end_date' => __('accounting-module.end-date')
        ];
    }
}
