<?php

namespace App\Http\Requests\Admin\EmployeeRewardDiscount;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRewardDiscountRequest extends FormRequest
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
            'cost' => 'required|numeric',
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
            'employee_data_id' => __('Employee'),
            'cost' => __('cost'),
        ];
    }
}
