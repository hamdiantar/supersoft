<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetEmployeeRequest extends FormRequest
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

    public function rules()
    {
        $id = request()->segment(5) ?? request()->segment(4);
//        dd($id);
        return [
            'employee_id' => 'required|exists:employee_data,id',
            'start_date' => 'required|string|max:50',
            'end_date' => 'nullable|max:50',
//            'phone' => 'required|string|max:50',
            'asset_id' => 'required|numeric|exists:assets_tb,id',
            'asset_employee_id' => '',

        ];
    }

    public function attributes()
    {
        return [
            'name' => __('name'),
            'phone' => __('phone'),
            'start_date' => __('start date'),
            'end_date' => __('end date'),
            'asset_id' => __('asset id'),
        ];
    }
}
