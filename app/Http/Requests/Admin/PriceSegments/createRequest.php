<?php

namespace App\Http\Requests\Admin\PriceSegments;

use Illuminate\Foundation\Http\FormRequest;

class createRequest extends FormRequest
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
            'name_en' => 'required|string|min:1|max:100',
            'name_ar' => 'required|string|min:1|max:100',
        ];
    }
}
