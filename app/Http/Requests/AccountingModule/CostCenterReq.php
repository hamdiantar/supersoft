<?php
namespace App\Http\Requests\AccountingModule;

use Illuminate\Foundation\Http\FormRequest;

class CostCenterReq extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name_ar' => 'required',
            'name_en' => 'required'
        ];
    }

    public function attributes() {
        return [
            'name_ar' => __('accounting-module.name-ar'),
            'name_en' => __('accounting-module.name-en')
        ];
    }
}
