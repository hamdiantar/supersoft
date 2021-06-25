<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $id = request()->segment(4);

        if(request()->has('password') && request()->password != null)
            $rules['password'] = 'required|string|min:5';

        if(request()->has('roles'))
            $rules['roles'] = 'exists:roles,id';

        $rules = [
            'name' => 'required | max:255',
            'is_admin_branch'=>'in:1,0',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['username'] =
            [
                'required','string',
                Rule::unique('users')->where(function ($query) use($id, $branch){
                    return $query->where('id','!=',$id)
                        ->where('username', request()->username)
//                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }
}
