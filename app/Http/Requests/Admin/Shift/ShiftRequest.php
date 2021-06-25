<?php

namespace App\Http\Requests\Admin\Shift;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShiftRequest extends FormRequest
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
        if(authIsSuperAdmin()){
            $branch = 'required|integer|exists:branches,id';

        } else {
            $branch = '';
        }
        $rules = [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'start_from' => 'required',
            'end_from' => 'required',
            'branch_id' => $branch,
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'start_from' => __('Start From'),
            'end_at' => __('End At'),
            'branch_id' => __('Branch')
        ];
    }
}
