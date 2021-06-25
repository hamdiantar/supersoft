<?php

namespace App\Http\Requests\Admin\EmployeeSalary;

use App\Models\EmployeeSalary;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeSalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $after = NULL;
        if (request()->has('employee_id') && request()->has('date')) {
            $start = (new Carbon(request('date')))->startOfMonth()->toDateString();
            $end = (new Carbon(request('date')))->endOfMonth()->toDateString();
            $exists = EmployeeSalary
                ::where('employee_id' ,request('employee_id'))
                ->whereDate('date' ,'>=' ,$start)
                ->whereDate('date' ,'<=' ,$end)
                ->first();
            if ($exists) {
                $after = $end;
            }
        }
        $rules =  [
            'employee_id' => 'required|exists:employee_data,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'insurances' => 'nullable|numeric',
            'allowances' => 'nullable|numeric',
            'date' => 'required|date'.($after ? '|after:'.$after : ''),
            'deportation_method' => 'required|in:bank,locker',
            'locker_id' => 'required_if:deportation_method,locker',
            'account_id' => 'required_if:deportation_method,bank',
            'pay_type' => 'required|in:cash,credit',
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

    function messages() {
        return [
            'date.after' => __('Salary Payed For This Month For This Employee')
        ];
    }

    function attributes()
    {
       return [
           'type' => __('type'),
           'employee_id' => __('Employee'),
           'date_from' => __('date from'),
           'date_to' => __('date to'),
           'deportation_method' => __('deportation method'),
           'pay_type' => __('Pay Type'),
           'cost_center_id' => __('accounting-module.cost-center')
       ];
    }
}
