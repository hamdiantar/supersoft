<?php

namespace App\Http\Requests\Admin\ServicesPackage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePackageRequest extends FormRequest
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
//            'branch_id' => 'required|exists:branches,id',
            'service_id' => 'required|exists:services,id',
            'discount_type' => 'required|in:value,percent',
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
                Rule::unique('service_packages')->where(function ($query) use($branch){
                    return $query
                        ->where('id','!=',$this->servicePackage->id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('service_packages')->where(function ($query) use($branch){
                    return $query->where('id','!=',$this->servicePackage->id)
                        ->where('name_ar', request()->name_ar)
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
            'branch_id' => __('Branch'),
            'service_id' => __('Service'),
            'discount_type' => __('Discount Type'),
        ];
    }
}
