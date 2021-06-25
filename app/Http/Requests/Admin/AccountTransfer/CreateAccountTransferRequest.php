<?php

namespace App\Http\Requests\Admin\AccountTransfer;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountTransferRequest extends FormRequest
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
            'account_from_id'=>'required|integer|exists:accounts,id',
            'account_to_id'=>'required|integer|exists:accounts,id',
            'date'=>'required|date',
            'amount'=>'required|numeric|min:0',
            'description'=>'nullable|string',
        ];

        if(authIsSuperAdmin())
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        
        return $rules;
    }
}
