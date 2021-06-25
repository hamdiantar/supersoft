<?php

namespace App\Http\Requests\Admin\Area;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreaRequest extends FormRequest
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
        $id = request()->segment(5) ?? request()->segment(4);
        $getIgnoredID = Area::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('areas', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('areas', 'name_en')->ignore($id).',deleted_at,NULL'];
        }
        else {
            $ruleAR = 'required|string|max:50|unique:areas,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:areas,name_en,NULL,id,deleted_at,NULL';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'city_id' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'city_id' => __('City')
        ];
    }
}
