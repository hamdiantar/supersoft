<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetTypeRequest extends FormRequest
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
        $getIgnoredID = AssetType::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('assets_types', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('assets_types', 'name_en')->ignore($id).',deleted_at,NULL'];
            $branch_id = 'required|numeric|exists:branches,id';
        }
        else {
            $ruleAR = 'required|string|max:50|unique:assets_types,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:assets_types,name_en,NULL,id,deleted_at,NULL';
            $branch_id = 'required|numeric|exists:branches,id';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'branch_id' => $branch_id,
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'branch_id' => __('branch id'),
        ];
    }
}
