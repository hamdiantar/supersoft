<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;

class createSettingsRequest extends FormRequest
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
            'sales_invoice_terms_ar'=>'nullable|string',
            'sales_invoice_terms_en'=>'nullable|string',
            'maintenance_terms_ar'=>'nullable|string',
            'maintenance_terms_en'=>'nullable|string',
            'sell_from_invoice_status'=>'required|string|in:new,old',
            'lat'=> 'required|numeric',
            'long'=> 'required|numeric',
            'kilo_meter_price'=> 'required|numeric|min:0',
            'quotation_terms_ar'=>'nullable|string',
            'quotation_terms_en'=>'nullable|string',
        ];
    }
}
