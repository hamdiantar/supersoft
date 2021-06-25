<?php

namespace App\Http\Requests\Admin\Authorization;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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

        $rules = [

            'name' => 'required|string|max:255',  //unique:roles,name,' . $id,
            'permissions' => 'required|exists:permissions,id',
        ];

        if(authIsSuperAdmin())
            $rules['branch_id'] = 'required|integer|exists:branches,id';

        return $rules;
    }
}
