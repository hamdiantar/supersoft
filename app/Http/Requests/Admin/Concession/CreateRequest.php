<?php

namespace App\Http\Requests\Admin\Concession;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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

            'date'=>'required|date',
            'time'=>'required',
            'status'=>'required|string|in:pending,accepted,finished,rejected',
            'concession_type_id'=> 'required|integer|exists:concession_types,id',
            'item_id'=>'required|integer',
            'description'=> 'nullable|string'
        ];

        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
