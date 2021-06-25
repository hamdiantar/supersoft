<?php
namespace App\Http\Requests\MoneyPermissions;

use Illuminate\Foundation\Http\FormRequest;

class LockerExchangeBankRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'cost_center_id' => 'required|exists:cost_centers,id',
            'permission_number' => 'required',
            'operation_date' => 'required|date',
            'employee_id' => 'required|exists:employee_data,id',
            'amount' => 'required|numeric|lte:from_locker_balance',
            'from_locker_id' => 'required|exists:lockers,id',
            'to_locker_id' => 'required|exists:accounts,id',
        ];
    }

    function attributes() {
        return [
            'branch_id' => __('Branch'),
            'cost_center_id' => __('accounting-module.cost-center'),
            'permission_number' => __('words.permission-number'),
            'operation_date' => __('Date'),
            'employee_id' => __('words.employee_name'),
            'amount' => __('the Amount'),
            'from_locker_id' => __('Locker From'),
            'to_locker_id' => __('words.bank-to'),
            'from_locker_balance' => __('words.available-amount')
        ];
    }
}
