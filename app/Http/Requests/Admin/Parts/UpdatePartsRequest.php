<?php

namespace App\Http\Requests\Admin\Parts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePartsRequest extends FormRequest
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

            'spare_part_unit_id' => 'required|integer|exists:spare_part_units,id',
            'suppliers_ids' => 'nullable',
            'description' => 'nullable|string',
            'part_in_store' => 'nullable|string',
            'img' => 'nullable|image',

            'spare_part_type_ids' => 'required',
            'spare_part_type_ids.*' => 'required|integer|exists:spare_parts,id',

//           UNITS
            'units.*.quantity' => 'required|integer|min:0',
            'units.*.unit_id' => 'required|integer|exists:spare_part_units,id',
            'units.*.selling_price' => 'required|numeric|min:0',
            'units.*.purchase_price' => 'required|numeric|min:0',
            'units.*.less_selling_price' => 'required|numeric|min:0',
            'units.*.service_selling_price' => 'required|numeric|min:0',
            'units.*.less_service_selling_price' => 'required|numeric|min:0',
            'units.*.maximum_sale_amount' => 'required|numeric|min:0',
            'units.*.minimum_for_order' => 'required|numeric|min:0',
            'units.*.biggest_percent_discount' => 'required|numeric|min:0',
            'units.*.barcode' => 'nullable|string|min:2',
            'units.*.supplier_barcode' => 'nullable|string|min:2',
            'units.*.damage_price' => 'required|numeric|min:0',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',

            'units.*.prices.*.name' => 'nullable|string',
            'units.*.prices.*.purchase_price' => 'nullable|numeric|min:0',
            'units.*.prices.*.sales_price' => 'nullable|numeric|min:0',
            'units.*.prices.*.maintenance_price' => 'nullable|numeric|min:0',

        ];

//        if (authIsSuperAdmin()) {
//
//            $rules['branch_id'] = 'required|integer|exists:branches,id';
//        }

        if (!request()->has('is_service')) {

            $rules['stores.*'] = 'integer|exists:stores,id';
        }

        $rules['name_en'] =
            [
                'required', 'max:255',
                Rule::unique('parts')->where(function ($query) {
                    return $query->where('id', '!=', $this->part->id)
                        ->where('name_en', request()->name_en)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required', 'max:255',
                Rule::unique('parts')->where(function ($query) {
                    return $query->where('id', '!=', $this->part->id)
                        ->where('name_ar', request()->name_ar)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'stores' => __('Store'),
            'spare_part_type_ids' => __('Main Parts Type'),
            'spare_part_unit_id' => __('Default unit'),
        ];
    }
}
