<?php

namespace App\Http\Requests\Admin\PointsRules;

use Illuminate\Foundation\Http\FormRequest;

class PointsRuleRequest extends FormRequest
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

            'points' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0',
            'text_ar' => 'required|string|max:191',
            'text_en' => 'required|string|max:191',
        ];

        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return  $rules;
    }
}
