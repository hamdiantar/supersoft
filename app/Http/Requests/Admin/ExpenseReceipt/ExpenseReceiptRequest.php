<?php

namespace App\Http\Requests\Admin\ExpenseReceipt;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseReceiptRequest extends FormRequest
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
        if(authIsSuperAdmin()){
           $branch = 'required|integer|exists:branches,id';
        }else{

            $branch = '';
        }
        return [
            'date' => 'required',
            'time' => 'required',
            'cost' => 'required|numeric',
            'deportation' => 'required|in:safe,bank',
            'expense_type_id' => 'required|exists:expenses_types,id',
            'expense_item_id' => 'required|exists:expenses_items,id',
            'branch_id' => $branch,
            'locker_id' => 'required_if:deportation,==safe|exists:lockers,id',
            'account_id' => 'required_if:deportation,==bank|exists:accounts,id',
            'payment_type' => 'required',
            'check_number' => 'required_if:payment_type,==,check',
            'bank_name' => 'required_if:payment_type,==,check',
            'cost_center_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'date' => __('Date'),
            'time' => __('Time'),
            'for' => __('For'),
            'receiver' => __('Receiver'),
            'cost' => __('Cost'),
            'deportation' => __('Deportation'),
            'branch_id' => __('Branch'),
            'expense_type_id' => __('Expense Type'),
            'expense_item_id' => __('Expense Item'),
            'payment_type' => __('Payment Type'),
            'bank_name' => __('Bank Name'),
            'check_number' => __('Check Number'),
            'cost_center_id' => __('accounting-module.cost-center')
        ];
    }
}
