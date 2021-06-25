<?php

namespace App\Http\Requests\Admin\ExpenseType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseTypeRequest extends FormRequest
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

//            'type_ar' => 'required|string|max:50',
//            'type_en' => 'required|string|max:50',
//            'branch_id' => 'required|exists:branches,id'
        ];

        if(authIsSuperAdmin()){

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['type_en'] =
            [
                'required','string','max:150',
                Rule::unique('expenses_types')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->expensesType->id)
                        ->where('type_en', request()->type_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['type_ar'] =
            [
                'required','string','max:150',
                Rule::unique('expenses_types')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->expensesType->id)
                        ->where('type_ar', request()->type_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;

    }

    public function attributes()
    {
        return [
            'type_ar' => __('Type in Arabic'),
            'type_en' => __('Type in English'),
            'branch_id' => __('Branch')
        ];
    }
}
