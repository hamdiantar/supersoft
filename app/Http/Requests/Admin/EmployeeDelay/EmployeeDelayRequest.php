<?php

namespace App\Http\Requests\Admin\EmployeeDelay;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDelayRequest extends FormRequest
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
            'employee_data_id' =>'required|integer|exists:employee_data,id',
            'type' => 'required',
            'date' => 'required',
            'number_of_hours' => 'required',
            'number_of_minutes' => 'required|numeric|min:0|max:60',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;
        }else{
            $branch = auth()->user()->branch_id;
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'branch_id' => __('Branch'),
            'type' => __('Type'),
            'number_of_hours' => __('Number Of Hours'),
            'number_of_minutes' => __('Number Of Minutes'),
            'employee_data_id' => __('Employee'),
        ];
    }
}
