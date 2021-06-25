<?php

namespace App\Http\Requests\Admin\EmployeeData;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDataCreateRequest extends FormRequest
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
            'country_id' =>'nullable|exists:countries,id',
            'employee_setting_id' =>'required|integer|exists:employee_settings,id',
            'city_id' =>'nullable|exists:cities,id',
            'area_id' =>'nullable|exists:areas,id',
            'cv' => 'mimes:pdf,doc,docx|max:2048',
            'start_date_assign' => 'required',
            'email' => 'nullable|email|max:128|unique:employee_data,email'
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
            'employee_setting_id' => __('employee setting'),
            'start_date_assign' => __('start date assign'),
            'cv' => __('cv'),
        ];
    }
}
