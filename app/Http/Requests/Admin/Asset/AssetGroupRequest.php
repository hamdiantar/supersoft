<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetGroupRequest extends FormRequest
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
        $getIgnoredID = AssetGroup::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('assets_groups', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('assets_groups', 'name_en')->ignore($id).',deleted_at,NULL'];
            $rateInp = 'required|numeric|min:0|max:100';
            $branch_id = 'required|numeric|exists:branches,id';
        }
        else {
            $ruleAR = 'required|string|max:50|unique:assets_groups,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:assets_groups,name_en,NULL,id,deleted_at,NULL';
            $rateInp = 'required|numeric|min:0|max:100';
            $branch_id = 'required|numeric|exists:branches,id';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'annual_consumtion_rate' => $rateInp,
            'branch_id' => $branch_id,
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'annual_consumtion_rate' => __('Annual Consumption Rate'),
            'name_en' => __('Name in English'),
            'branch_id' => __('branch id'),
        ];
    }
}
