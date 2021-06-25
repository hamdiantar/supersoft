<?php

namespace App\Http\Requests\Admin\EmployeeSettings;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSettingCreateRequest extends FormRequest
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
            'name_ar'=>'required|string|max:255',
            'name_en'=>'required|string|max:255',
            'time_attend'=>'required',
            'time_leave'=>'required',
            'daily_working_hours'=>'required',
            'annual_vocation_days'=>'required',
            'max_advance'=>'required',
            'amount_account'=>'required',
            'type_absence_equal'=>'required',
            'hourly_extra_equal'=>'required',
            'hourly_delay_equal'=>'required',
            'shift_id'=>'nullable|integer|exists:shifts,id',
            'card_work_percent' => 'nullable|numeric|min:0|max:100'
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
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'branch_id' => __('Branch'),
            'time_attend' => __('time attend'),
            'time_leave' => __('time leave'),
            'amount_account' => __('amount account'),
            'max_advance' => __('max advance'),
            'type_absence_equal' => __('type absence equal'),
            'hourly_extra_equal' => __('hourly extra equal'),
            'hourly_delay_equal' => __('hourly delay equal'),
            'shift_id' => __('Shift'),
            'annual_vocation_days' => __('annual vocation days'),
            'daily_working_hours' => __('daily working hours'),
        ];
    }
}
