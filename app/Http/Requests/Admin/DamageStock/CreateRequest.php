<?php

namespace App\Http\Requests\Admin\DamageStock;

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
        $rules = [
            'date' => 'required|date',
            'time' => 'required',
            'type' => 'required|string|in:natural,un_natural',
            'description' => 'nullable|string',
            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.store_id' => 'required|integer|exists:stores,id',
            'items.*.part_price_segment_id' => 'nullable',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.price' => 'required|numeric|min:0',
        ];

        if (request()->has('employees')){

            $rules['employees.*.employee_id'] = 'required|integer|exists:employee_data,id';
            $rules['employees.*.percent'] = 'required|numeric|max:100';
        }

        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
