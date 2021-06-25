<?php

namespace App\Http\Requests\Admin\Cars;

use Illuminate\Foundation\Http\FormRequest;

class AddCarRequest extends FormRequest
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
            'customer_id'=>'required|integer|exists:customers,id',
            'type_id'=>'nullable|integer|exists:car_types,id',
            'company_id'=>'nullable|integer|exists:companies,id',
            'model_id'=>'nullable|integer|exists:car_models,id',
            'plate_number'=>'required|string|max:190',
            'Chassis_number'=>'nullable|string|max:190',
            'speedometer'=>'nullable|string|max:190',
            'motor_number'=>'nullable|string|max:190',
            'color'=>'nullable|string|max:100',
            'barcode'=> 'nullable|string|unique:cars,barcode',
            'image'=> 'nullable|image|mimes:jpg,png,jpeg',
        ];
    }
}
