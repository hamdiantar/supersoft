<?php

namespace App\Http\Requests\Admin\RevenueReceipt;

use Illuminate\Foundation\Http\FormRequest;

class RevenueReceiptRequest extends FormRequest
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
            'receiver' => 'required|string',
            'for' => 'nullable|string',
            'deportation' => 'required|in:safe,bank',
            'revenue_type_id' => 'required|exists:revenue_types,id',
            'revenue_item_id' => 'required|exists:revenue_items,id',
            'branch_id' => $branch,
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
            'cost' => __('Cost'),
            'for' => __('For'),
            'receiver' => __('Receiver'),
            'deportation' => __('Deportation'),
            'branch_id' => __('Branch'),
            'revenue_type_id' => __('Revenue Type'),
            'revenue_item_id' => __('Revenue Item'),
            'payment_type' => __('Payment Type'),
            'bank_name' => __('Bank Name'),
            'check_number' => __('Check Number'),
            'cost_center_id' => __('accounting-module.cost-center')
        ];
    }
}
