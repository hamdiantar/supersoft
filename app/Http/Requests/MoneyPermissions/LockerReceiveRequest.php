<?php
namespace App\Http\Requests\MoneyPermissions;

use Illuminate\Foundation\Http\FormRequest;

class LockerReceiveRequest extends FormRequest
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
            'locker_exchange_permission_id' => 'required',
            'cost_center_id' => 'required|exists:cost_centers,id',
            'permission_number' => 'required',
            'operation_date' => 'required|date',
            'employee_id' => 'required|exists:employee_data,id',
            'amount' => 'required|numeric',
        ];
    }

    function attributes() {
        return [
            'locker_exchange_permission_id' => __('words.locker-exchange-permission'),
            'cost_center_id' => __('accounting-module.cost-center'),
            'permission_number' => __('words.permission-number'),
            'operation_date' => __('Date'),
            'employee_id' => __('words.employee_name'),
            'amount' => __('the Amount'),
        ];
    }
}
