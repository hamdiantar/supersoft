<?php
namespace App\Http\Requests\MoneyPermissions;

use Illuminate\Foundation\Http\FormRequest;

class BankReceiveEditRequest extends FormRequest
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
            'cost_center_id' => 'required|exists:cost_centers,id',
            'operation_date' => 'required|date',
            'employee_id' => 'required|exists:employee_data,id'
        ];
    }

    function attributes() {
        return [
            'cost_center_id' => __('accounting-module.cost-center'),
            'operation_date' => __('Date'),
            'employee_id' => __('words.employee_name')
        ];
    }
}
