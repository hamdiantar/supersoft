<?php

namespace App\Http\Requests\Admin\Currency;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
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
        $getIgnoredID = Currency::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('currencies', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('currencies', 'name_en')->ignore($id).',deleted_at,NULL'];
        }
        else {
            $ruleAR = 'required|string|max:50|unique:currencies,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:currencies,name_en,NULL,id,deleted_at,NULL';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'symbol_ar' => 'required|string|max:50',
            'symbol_en' => 'required|string|max:50',
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'symbol_ar' => __('Symbol in Arabic'),
            'symbol_en' => __('Symbol in English')
        ];
    }
}
