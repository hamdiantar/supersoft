<?php

namespace App\Http\Requests\Admin\SuppliersGroups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierGroupRequest extends FormRequest
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

//            'branch_id'=>'required|integer|exists:branches,id',
//            'name_en'=>'required|string|max:200',
//            'name_ar'=>'required|string|max:200',
            'discount'=> 'required|numeric|min:0',
            'discount_type'=> 'nullable|in:amount,percent',
            'description'=> 'nullable|string',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }
        $_id = request()->has('_id') ? request('_id') : NULL;
        $rules['name_en'] =
            [
                'required','string','max:150',
                Rule::unique('supplier_groups')->where(function ($query) use($branch, $_id){
                    return $query->where('id', '!=', $_id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('supplier_groups')->where(function ($query) use($branch, $_id){
                    return $query->where('id', '!=', $_id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];
        return $rules;
    }
}
