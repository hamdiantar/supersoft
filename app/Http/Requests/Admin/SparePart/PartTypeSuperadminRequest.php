<?php

namespace App\Http\Requests\Admin\SparePart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartTypeSuperadminRequest extends FormRequest
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
        $_id = request()->has('_id') ? request('_id') : NULL;
        return [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'branch_id' => 'required|integer|exists:branches,id',
            'type_ar' => [
                'required',
                'max:100',
                'string',
                Rule::unique('spare_parts')->where(function ($query) use ($_id) {
                    return $query->where('type_ar', request()->type_ar)
                        ->where('branch_id', request()->branch_id)
                        ->where('spare_part_id', null)
                        ->where('deleted_at', null)
                        ->when($_id ,function ($q) use ($_id) {
                            $q->where('id' ,'!=' ,$_id);
                        });
                })
            ],
            'type_en' => [
                'required',
                'max:100',
                'string',
                Rule::unique('spare_parts')->where(function ($query) use ($_id)  {
                    return $query->where('type_en', request()->type_en)
                        ->where('branch_id', request()->branch_id)
                        ->where('spare_part_id', null)
                        ->where('deleted_at', null)
                        ->when($_id ,function ($q) use ($_id) {
                            $q->where('id' ,'!=' ,$_id);
                        });
                })
            ]
        ];
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
