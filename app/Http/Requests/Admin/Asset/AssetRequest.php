<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetRequest extends FormRequest
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
            'name_ar' => 'required|string|max:50',
            'name_en' => 'required|string|max:50',
            'branch_id' => 'required|numeric|exists:branches,id',
            'asset_group_id' => 'required|exists:assets_groups,id',
            'asset_type_id' => 'required|exists:assets_types,id',
            'asset_status' => 'required|numeric|min:1|max:3',
             'annual_consumtion_rate' => 'required|numeric',
//            'asset_details' => 'required',
//            'purchase_date' => 'required',
//            'date_of_work' => 'required',
            'purchase_cost' => 'required|numeric',

        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'annual_consumtion_rate' => __('Annual Consumption Rate'),
            'name_en' => __('Name in English'),
            'branch_id' => __('branch id'),
            'asset_group_id' => __('asset group'),
            'asset_type_id' => __('asset type'),
            'asset_status' => __('asset status'),
            'details' => __('details'),
            'purchase_date' => __('purchase date'),
            'date_of_work' => __('date of work'),
            'purchase_cost' => __('purchase cost'),
        ];
    }
}
