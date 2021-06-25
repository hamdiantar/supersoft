<?php

namespace App\Http\Requests\AccountingModule\AccountRelations;

use Illuminate\Foundation\Http\FormRequest;

class LockersBanksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'accounts_tree_root_id' => 'required|exists:accounts_trees,id',
            'accounts_tree_id' => 'required|exists:accounts_trees,id',
            'locker_id' => 'required_without:bank_id',
            'bank_id' => 'required_without:locker_id',
            'account_nature' => 'required|in:bank_acc,locker'
        ];
    }

    public function attributes() {
        return [
            'accounts_tree_root_id' => __('accounting-module.account-root'),
            'accounts_tree_id' => __('accounting-module.account-branch'),
            'bank_id' => __('accounting-module.type_id'),
            'locker_id' => __('accounting-module.type_id'),
            'account_nature' => __('accounting-module.account-nature')
        ];
    }
}
