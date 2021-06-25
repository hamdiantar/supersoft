<?php

namespace App\Http\Requests\Admin\PointSettings;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'points'=> 'required|integer|min:0',
            'amount'=>'required|numeric|min:0',
        ];
    }
}
