<?php

namespace App\Http\Requests\Admin\Authorization;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class
CreateRoleRequest extends FormRequest
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

            'name'=>'required|string|max:255',
            'permissions'=>'required|exists:permissions,id',
        ];

        if(authIsSuperAdmin()){

            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
