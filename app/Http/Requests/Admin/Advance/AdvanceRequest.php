<?php

namespace App\Http\Requests\Admin\Advance;

use Illuminate\Foundation\Http\FormRequest;

class AdvanceRequest extends FormRequest
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
            'deportation' => 'required|in:safe,bank',
            'operation' => 'required|in:withdrawal,deposit',
            'amount' => 'required',
            'date' => 'required',
            'account_id' => 'required_if:deportation,bank',
            'locker_id' => 'required_if:deportation,safe',
            'cost_center_id' => 'required'
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
            'employee_data_id' => __('Employee'),
            'deportation' => __('deportation'),
            'operation' => __('operation'),
            'date' => __('date'),
            'account_id' => __('Account'),
            'locker_id' => __('Locker'),
            'amount' => __('amount'),
            'cost_center_id' => __('accounting-module.cost-center')
        ];
    }
}
