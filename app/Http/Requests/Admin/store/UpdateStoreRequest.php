<?php

namespace App\Http\Requests\Admin\store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends FormRequest
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

//            'name_ar' => 'required|string|max:50',
//            'name_en' => 'required|string|max:50',
//            'employees_ids' => 'required',
//            'store_phone' => 'string',
//            'branch_id' => 'required|integer|exists:branches,id',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }


        $rules['name_ar'] =
            [
                'required','max:100','string',
                Rule::unique('stores')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->store->id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_en'] =
            [
                'required','max:100','string',
                Rule::unique('stores')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->store->id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];


        return $rules;
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'employees_ids' => __('Store Creator'),
            'store_phone' => __('Store Phone'),
            'branch_id' => __('Branch'),
        ];
    }
}
