<?php

namespace App\Http\Requests\Admin\companies;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
        ];
    }
}
