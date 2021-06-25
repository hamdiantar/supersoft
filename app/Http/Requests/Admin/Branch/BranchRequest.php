<?php

namespace App\Http\Requests\Admin\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name_ar' => 'required|string|max:50|unique:branches,name_ar',
            'name_en' => 'required|string|max:50|unique:branches,name_en',
            'country_id' => 'required|integer',
            'city_id' => 'required|integer',
            'currency_id' => 'integer',
            'email' =>'nullable|email',
            'address_ar' =>'required|string',
            'address_en' =>'required|string',
            'mailbox_number' => 'nullable',
            'postal_code' =>'nullable',
            'phone1' =>'nullable',
            'fax' =>'nullable',
            'logo' =>'image|mimes:jpeg,png,jpg,gif,svg',
            'map' =>'image|mimes:jpeg,png,jpg,gif,svg',
//            'tax_card' =>'string',
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'country_id' => __('Country'),
            'city_id' => __('City'),
            'area_id' => __('Area'),
            'currency_id' => __('Currency'),
            'email' => __('Email'),
            'address_ar' => __('Address in Arabic'),
            'address_en' => __('Address in English'),
            'mailbox_number' => __('Commercial Register'),
            'postal_code' => __('Postal code'),
            'phone1' => __('Phone 1'),
            'phone2' =>__('Phone 2'),
            'fax' => __('Fax'),
            'logo'=> __('Logo'),
            'map' => __('Map'),
            'vat_active' =>__('Is VAT active ?'),
            'vat_percent' =>__('VAT percent'),
        ];
    }
}
