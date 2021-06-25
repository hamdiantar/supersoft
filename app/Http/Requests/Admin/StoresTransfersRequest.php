<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoresTransfersRequest extends FormRequest
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
            'store_from_id' => 'required|exists:stores,id',
            'store_to_id' => 'required|exists:stores,id|different:store_from_id',
            'items.*' => 'required',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.part_price_segment_id' => 'nullable|integer|exists:part_price_segments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'description' => 'nullable:string'
        ];


        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|exists:branches,id';
        }

        return $rules;
    }

    function attributes()
    {
        return [
            'date' => __('words.transfer-date'),
            'branch_id' => __('words.branch_id'),
            'time' => __('words.transfer-time'),
            'store_from_id' => __('words.store-from'),
            'store_to_id' => __('words.store-to'),
            'parts' => __('words.part-transfer')
        ];
    }
}
