<?php

namespace App\Http\Requests\Admin\CustomerCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerCategoryRequest extends FormRequest
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

//            'name_ar'=>'required|string|max:255',
//            'name_en'=>'required|string|max:255',
//            'branch_id'=>'required|integer|exists:branches,id',
            'sales_discount_type'=>'nullable|in:amount,percent',
            'services_discount_type'=>'nullable|in:amount,percent',
            'sales_discount'=>'nullable',
            'services_discount'=>'nullable',
            'description'=>'nullable|string',
        ];

        if(authIsSuperAdmin()){

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['name_en'] =
            [
                'required','string','max:150',
                Rule::unique('customer_categories')->where(function ($query) use($branch){
                    return $query->where('id', '!=', $this->customers_category->id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('customer_categories')->where(function ($query) use($branch){
                    return $query->where('id', '!=', $this->customers_category->id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

//        $rules['email'] =
//            [
//                'required','email','max:255',
//                Rule::unique('customer_categories')->where(function ($query) use($branch){
//                    return $query->where('id', '!=', $this->customers_category->id)
//                        ->where('email', request()->email)
//                        ->where('branch_id', $branch)
//                        ->where('deleted_at', null);
//                }),
//            ];

        return $rules;
    }
}
