<?php

namespace App\Http\Requests\Admin\Suppliers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSuppliersRequest extends FormRequest
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
//        {$this->post->id}

        $rules = [
//            'name_en'=>'required|string|max:255',
//            'name_ar'=>'required|string|max:255',
            'country_id'=>'nullable|integer|exists:countries,id',
            'city_id'=>'nullable|integer|exists:cities,id',
            'area_id'=>'nullable|integer|exists:areas,id',
            'group_id'=>'nullable|integer|exists:supplier_groups,id',
            'sub_group_id'=>'nullable|integer|exists:supplier_groups,id',
            'phone_1'=>'nullable|string', //|unique:suppliers,phone_1,NULL,id,deleted_at,NULL
            'phone_2'=>'nullable|string', //|unique:suppliers,phone_2,NULL,id,deleted_at,NULL
            'address'=>'nullable|string:max:255',
            'type'=>'required|string|in:person,company',
//            'email'=>'nullable|email',  //|unique:suppliers,email,NULL,id,deleted_at,NULL
            'fax'=>'nullable|string|max:255',
            'commercial_number'=>'nullable|string|max:255',
            'tax_card'=>'nullable|string|max:255',
            'description'=>'nullable|string',
            'tax_number'=>'nullable|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'maximum_fund_on' => 'numeric|min:0',
            'identity_number' => 'nullable',

            'contacts'=>'nullable',
            'contacts.*.phone_1'=>'required|string',
            'contacts.*.name'=>'required|string|min:1|max:100',
            'contacts.*.phone_2'=>'nullable|string',
            'contacts.*.address'=>'nullable|string',
            'supplier_type'=>'required|in:supplier,contractor,both_together',
            'bankAccount.*.bank_name' => 'required',
            'bankAccount.*.account_number' => 'required',
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
                Rule::unique('suppliers')->where(function ($query) use($branch){
                    return $query->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('suppliers')->where(function ($query) use($branch){
                    return $query->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['email'] =
            [
                'nullable','email','max:255',
                Rule::unique('suppliers')->where(function ($query) use($branch){
                    return $query->where('email', request()->email)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];
        return $rules;
    }

    public function attributes()
    {
       return [
         'supplier_type' => __('Supplier Type')
       ];
    }

    public function messages()
    {
        return [
            'contacts.*.phone_1.required' => __('contact phone 1 is required'),
            'contactsUpdate.*.phone_1.required' => __('contact phone 1 is required'),
            'contacts.*.name.required' => __('contact name is required'),
            'contactsUpdate.*.name.required' => __('contact name is required'),
            'bankAccount.*.bank_name.required' => __('bank name is required'),
            'bankAccount.*.account_number.required' => __('account number is required'),
            'bankAccountUpdate.*.bank_name.required' => __('bank name is required'),
            'bankAccountUpdate.*.account_number.required' => __('account number is required'),
        ];
    }
}
