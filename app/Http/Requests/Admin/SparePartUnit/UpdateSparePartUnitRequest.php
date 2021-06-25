<?php

namespace App\Http\Requests\Admin\SparePartUnit;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSparePartUnitRequest extends FormRequest
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
            'unit_ar' => 'required|string|max:50|unique:spare_part_units,unit_ar,'.$this->sparePartUnit->id,
            'unit_en' => 'required|string|max:50|unique:spare_part_units,unit_en,'.$this->sparePartUnit->id,
        ];
    }

    public function attributes()
    {
        return [
            'unit_ar' => __('Unit In Arabic'),
            'unit_en' => __('Unit In English'),
        ];
    }
}
