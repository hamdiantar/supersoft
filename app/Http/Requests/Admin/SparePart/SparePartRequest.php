<?php

namespace App\Http\Requests\Admin\SparePart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SparePartRequest extends FormRequest
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
            'image' =>'image|mimes:jpeg,png,jpg,gif,svg',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $type_ar_unique_rule = Rule::unique('spare_parts')->where(function ($query) use($branch){

            return $query->where('type_ar', request()->type_ar)
                ->where('branch_id', $branch)
                ->where('spare_part_id', null)
                ->where('deleted_at', null);
        });

        $type_en_unique_rule = Rule::unique('spare_parts')->where(function ($query) use($branch){
            return $query->where('type_en', request()->type_en)
                ->where('branch_id', $branch)
                ->where('spare_part_id', null)
                ->where('deleted_at', null);
        });

        $rules['type_ar'] =
            [
                'required','max:100','string', $type_ar_unique_rule
            ];

        $rules['type_en'] =
            [
                'required','max:100','string', $type_en_unique_rule
            ];

       return $rules;
    }

    public function attributes()
    {
        return [
            'type_ar' => __('Type In Arabic'),
            'type_en' => __('Type In English'),
            'branch_id' => __('Branch'),
            'image' => __('Image')
        ];
    }
}
