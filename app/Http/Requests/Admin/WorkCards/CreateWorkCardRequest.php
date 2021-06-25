<?php

namespace App\Http\Requests\Admin\WorkCards;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkCardRequest extends FormRequest
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

            'customer_id' => 'required|integer|exists:customers,id',
            'receive_car_date' => 'required|date',
            'receive_car_time' => 'required',
            'delivery_car_date' => 'required|date',
            'delivery_car_time' => 'required',
            'car_id' => 'required|integer|exists:cars,id',
            'note' => 'nullable|string',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
