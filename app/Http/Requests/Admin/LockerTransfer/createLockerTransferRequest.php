<?php

namespace App\Http\Requests\Admin\LockerTransfer;

use Illuminate\Foundation\Http\FormRequest;

class createLockerTransferRequest extends FormRequest
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

            'locker_from_id'=>'required|integer|exists:lockers,id',
            'locker_to_id'=>'required|integer|exists:lockers,id',
            'date'=>'required|date',
            'amount'=>'required|numeric|min:0',
            'description'=>'nullable|string',
        ];

        if(authIsSuperAdmin())
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        
        return $rules;
    }
}
