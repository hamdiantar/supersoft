<?php

namespace App\Http\Requests\Admin\OpeningBalance;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'operation_date' => 'required|date',
            'operation_time' => 'required',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.buy_price' => 'required|numeric|min:0',
            'items.*.store_id' => 'required|integer|exists:stores,id',
            'items.*.part_price_price_segment_id' => 'nullable|integer|exists:part_price_segments,id'
        ];
    }

    public function attributes() {
        return [
            'branch_id' => __('opening-balance.branch'),
            'serial_number' => __('opening-balance.serial-number'),
            'operation_date' => __('opening-balance.operation-date'),
            'operation_time' => __('opening-balance.operation-time'),
            'items.*.part_price_id' => __('opening-balance.items-unit'),
            'items.*.quantity' => __('opening-balance.items-qnty'),
            'items.*.buy_price' => __('opening-balance.items-price'),
            'items.*.store_id' => __('opening-balance.items-store')
        ];
    }
}
