<?php

namespace App\Http\Requests\Web\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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

        $rules = [

            'type_id'=>'nullable|integer|exists:car_types,id',
            'company_id'=>'nullable|integer|exists:companies,id',
            'model_id'=>'nullable|integer|exists:car_models,id',
            'plate_number'=>'required|string|max:190',
            'Chassis_number'=>'nullable|string|max:190',
            'speedometer'=>'nullable|string|max:190',
            'motor_number'=>'nullable|string|max:190',
            'color'=>'nullable|string|max:100',
            'image'=> 'nullable|image|mimes:jpg,png,jpeg',
        ];

        $rules['barcode'] =
            [
                'nullable','string',
                Rule::unique('cars')->where(function ($query){
                    return $query->where('id','!=',$this->car->id)->where('deleted_at', null);
                }),
            ];

        return $rules;
    }
}
