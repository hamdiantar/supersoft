<?php

namespace App\Http\Requests\Admin\Accounts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountsRequest extends FormRequest
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
//            'number'=>'required|string|max:150|unique:accounts,number,'.$this->account->id.',id,deleted_at,NULL',
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
                Rule::unique('accounts')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->account->id)
                        ->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('accounts')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->account->id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['number'] =
            [
                'required','string','max:150',
                Rule::unique('accounts')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->account->id)
                        ->where('number', request()->number)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        if(!$this->account->revenueReceipts->count() && !$this->account->expensesReceipts->count())
            $rules['balance'] =  'required|numeric|min:0';


        return $rules;
    }
}
