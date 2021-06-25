<?php

namespace App\Http\Requests\Admin\LockersTransactions;

use Illuminate\Foundation\Http\FormRequest;

class CreateLockersTransactionsRequest extends FormRequest
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

            'locker_id'=>'required|integer|exists:lockers,id',
            'account_id'=>'required|integer|exists:accounts,id',
            'type'=>'required|string|in:deposit,withdrawal',
            'locker_balance'=>'required|numeric|min:0',
            'account_balance'=>'required|numeric|min:0',
            'date'=>'required|date',
        ];

        if(request()->has('type') && request()->type == 'deposit')
            $rules['amount'] = 'required|numeric|min:0|lte:account_balance';

        if(request()->has('type') && request()->type == 'withdrawal')
            $rules['amount'] = 'required|numeric|min:0|lte:locker_balance';

        if(authIsSuperAdmin())
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        
       return $rules;
    }
}
