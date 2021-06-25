<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        $branch = request()->branch_id;

        $rules = [
            'name'=>'required|string|max:150',
            'address'=>'nullable|string|max:200',
            'password'=>'required|string|min:8',
            'branch_id'=>'required|integer|exists:branches,id',
        ];

        $rules['phone'] =
            [
                'required','string',
                Rule::unique('customer_requests')->where(function ($query) use($branch){
                    return $query->where('phone', request()->phone)
                        ->where('branch_id', $branch)->whereIn('status',['approved','pending']);
                }),
            ];

        $rules['username'] =
            [
                'required','string',
                Rule::unique('customer_requests')->where(function ($query) use($branch){
                    return $query->where('username', request()->username)
                        ->where('branch_id', $branch)->whereIn('status',['approved','pending']);
                }),
            ];

        return $rules;
    }
}
