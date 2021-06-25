<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeAbsenceReq extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'employee_id' => 'required|exists:employee_data,id',
            'absence_days' => 'required|numeric',
            'absence_type' => 'required|in:vacation,absence',
            'date' => 'required|date'
        ];
    }
}
