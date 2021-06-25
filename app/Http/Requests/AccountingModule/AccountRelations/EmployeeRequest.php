<?php

namespace App\Http\Requests\AccountingModule\AccountRelations;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'related_as' => 'required|in:global,actor_group,actor_id',
            'group_id' => 'required_if:related_as,actor_group',
            'employee_id' => 'required_if:related_as,actor_id'
        ];
    }

    public function attributes() {
        return [
            'accounts_tree_root_id' => __('accounting-module.account-root'),
            'accounts_tree_id' => __('accounting-module.account-branch'),
            'related_as' => __('accounting-module.related_as'),
            'group_id' => __('accounting-module.actor_group'),
            'employee_id' => __('accounting-module.actor_id')
        ];
    }
}
