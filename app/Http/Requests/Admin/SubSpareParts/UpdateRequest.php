<?php

namespace App\Http\Requests\Admin\SubSpareParts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'spare_part_id' => 'required|integer|exists:spare_parts,id'
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        } else {

            $branch = auth()->user()->branch_id;
        }

        $rules['type_ar'] =
            [
                'required', 'max:100', 'string',
                Rule::unique('spare_parts')->where(function ($query) use ($branch) {
                    return $query->where('id', '!=', $this->sparePart->id)
                        ->where('type_ar', request()->type_ar)
                        ->where('branch_id', $branch)
                        ->where('spare_part_id', '!=', null)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['type_en'] =
            [
                'required', 'max:100', 'string',
                Rule::unique('spare_parts')->where(function ($query) use ($branch) {
                    return $query->where('id', '!=', $this->sparePart->id)
                        ->where('type_en', request()->type_en)
                        ->where('branch_id', $branch)
                        ->where('spare_part_id', '!=', null)
                        ->where('deleted_at', null);
                }),
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
