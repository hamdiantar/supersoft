<?php

namespace App\Http\Requests\Admin\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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

            'type_id'=>'required|integer|exists:service_types,id',
//            'name_en'=>'required|string|max:200',
//            'name_ar'=>'required|string|max:200',
            'description_en'=>'nullable|string',
            'description_ar'=>'nullable|string',
            'price'=>'Required|numeric|min:0',
            'hours'=>'Required|integer|min:0',
            'minutes'=>'Required|integer|min:0|max:60',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['name_en'] =
            [
                'required','string','max:200',
                Rule::unique('services')->where(function ($query) use($branch){
                    return $query->where('id','!=',$this->service->id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:200',
                Rule::unique('services')->where(function ($query) use($branch){
                    return $query->where('id','!=',$this->service->id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }
}
