<?php
namespace App\OpeningStockBalance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpeningBalanceEditRequest extends FormRequest
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
            'serial_number' => 'required',
            'operation_date' => 'required|date',
            'operation_time' => 'required',
            'table_data.*.unit_id' => 'required',
            'table_data.*.quantity' => 'required|numeric',
            'table_data.*.buy_price' => 'required|numeric',
            'table_data.*.store_id' => 'required'
        ];
    }

    public function attributes() {
        return [
            'serial_number' => __('opening-balance.serial-number'),
            'operation_date' => __('opening-balance.operation-date'),
            'operation_time' => __('opening-balance.operation-time'),
            'table_data.*.unit_id' => __('opening-balance.items-unit'),
            'table_data.*.quantity' => __('opening-balance.items-qnty'),
            'table_data.*.buy_price' => __('opening-balance.items-price'),
            'table_data.*.store_id' => __('opening-balance.items-store')
        ];
    }
}
