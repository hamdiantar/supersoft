<?php

namespace App\Http\Requests\AccountingModule;

use Illuminate\Foundation\Http\FormRequest;

class AccountRelationReq extends FormRequest
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
            'accounts_tree_root_id' => 'required|exists:accounts_trees,id',
            'accounts_tree_id' => 'required|exists:accounts_trees,id',
            'account_type_id' => 'required',
            'account_item_id' => 'required',
            'account_nature' => 'required|in:debit,credit'
        ];
    }

    public function attributes() {
        return [
            'accounts_tree_root_id' => __('accounting-module.account-root'),
            'accounts_tree_id' => __('accounting-module.account-branch'),
            'account_type_id' => __('accounting-module.account-type'),
            'account_item_id' => __('accounting-module.account-item'),
            'account_nature' => __('accounting-module.account-nature')
        ];
    }
}
