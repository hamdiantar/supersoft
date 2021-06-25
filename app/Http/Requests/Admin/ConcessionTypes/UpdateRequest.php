<?php

namespace App\Http\Requests\Admin\ConcessionTypes;

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
        $id = $this->concession_type->id;

        $rules = [
            'description'=>'nullable|string|min:1',
            'type'=>'required|string|in:add,withdrawal',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['name_ar'] = [

            'required', 'string', 'min:1', 'max:100',
            Rule::unique('concession_types')->where(function ($query) use ($branch, $id) {

                return $query->where('id','!=',$id)
                    ->where('name_ar', request()->name_ar)
                    ->where('branch_id', $branch)
                    ->where('deleted_at', null);
            })
        ];

        $rules['name_en'] = [

            'required', 'string', 'min:1', 'max:100',
            Rule::unique('concession_types')->where(function ($query) use ($branch, $id) {

                return $query->where('id','!=',$id)
                    ->where('name_en', request()->name_ar)
                    ->where('branch_id', $branch)
                    ->where('deleted_at', null);
            })
        ];

        $rules['concession_type_item_id'] =
            [
                'nullable','integer','exists:concession_type_items,id',
                Rule::unique('concession_types')->where(function ($query) use($branch, $id){
                    return $query->where('id','!=',$id)
                        ->where('branch_id', $branch)
                        ->where('concession_type_item_id', request()->concession_type_item_id)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }
}
