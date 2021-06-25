<?php

namespace App\Http\Requests\Admin\carTypes;

use Illuminate\Foundation\Http\FormRequest;

class carTypesRequest extends FormRequest
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
            'type_ar' => 'required',
            'type_en' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type_ar' => __('Type in Arabic'),
            'type_en' => __('Type in English'),
        ];
    }
}
