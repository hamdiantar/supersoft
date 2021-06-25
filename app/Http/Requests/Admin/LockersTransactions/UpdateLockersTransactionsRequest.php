<?php

namespace App\Http\Requests\Admin\LockersTransactions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLockersTransactionsRequest extends FormRequest
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
            'date'=>'required|date',
            'amount'=>'required|numeric|min:0',
        ];
    }
}
