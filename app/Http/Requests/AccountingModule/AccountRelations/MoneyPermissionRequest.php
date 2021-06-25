<?php

namespace App\Http\Requests\AccountingModule\AccountRelations;

use Illuminate\Foundation\Http\FormRequest;

class MoneyPermissionRequest extends FormRequest
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
            'permission_nature' => 'required|in:exchange,receive',
            'account_nature' => 'required|in:permission_locker,permission_bank'
        ];
    }

    public function attributes() {
        return [
            'accounts_tree_root_id' => __('accounting-module.account-root'),
            'accounts_tree_id' => __('accounting-module.account-branch'),
            'permission_nature' => __('accounting-module.permission-nature'),
            'account_nature' => __('accounting-module.account-nature')
        ];
    }
}
