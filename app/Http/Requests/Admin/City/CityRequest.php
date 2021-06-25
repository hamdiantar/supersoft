<?php

namespace App\Http\Requests\Admin\City;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
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
        $getIgnoredID = City::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('cities', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('cities', 'name_en')->ignore($id).',deleted_at,NULL'];
        }
        else {
            $ruleAR = 'required|string|max:50|unique:cities,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:cities,name_en,NULL,id,deleted_at,NULL';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'country_id' => 'required|exists:countries,id'
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'country_id' => __('Country')
        ];
    }
}
