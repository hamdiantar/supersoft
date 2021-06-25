<?php

namespace App\Http\Requests\Admin\Lockers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLockerRequest extends FormRequest
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

//            'name_en'=>'required|string|max:150',
//            'name_ar'=>'required|string|max:150',
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
                Rule::unique('lockers')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->locker->id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('lockers')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->locker->id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        if(!$this->locker->revenueReceipts->count() && !$this->locker->expensesReceipts->count())
            $rules['balance'] =  'required|numeric|min:0';

        return $rules;
    }
}
