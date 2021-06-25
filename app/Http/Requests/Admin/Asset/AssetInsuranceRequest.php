<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetInsuranceRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:50',
            'start_date' => 'required|string|max:50',
            'end_date' => 'required|max:50',
            'asset_id' => 'required|numeric|exists:assets_tb,id',
            'asset_insurance_id' => '',
            
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('name'),
            'start_date' => __('start date'),
            'end_date' => __('end date'),
            'asset_id' => __('asset id'),
        ];
    }
}
