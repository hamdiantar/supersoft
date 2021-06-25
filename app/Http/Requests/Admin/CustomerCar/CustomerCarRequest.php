<?php

namespace App\Http\Requests\Admin\CustomerCar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerCarRequest extends FormRequest
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

            'city_id'=>'exists:cities,id',
            'area_id'=>'nullable|integer|exists:areas,id',
            'customer_category_id'=>'nullable|exists:customer_categories,id',
            'type'=>'required|string|in:person,company',
            'status'=>'required|in:1,0',
            'cars_number'=>'required|numeric|min:0',
            'password'   =>'required|string|min:8',
//            'tax_number'=>'nullable|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'maximum_fund_on' => 'numeric|min:0',

            'contacts'=>'nullable',
            'contacts.*.phone_1'=>'nullable|string',
            'contacts.*.phone_2'=>'nullable|string',
            'contacts.*.address'=>'nullable|string',
            'contacts.*.name'=> 'required|string|min:1|max:100',
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
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('name_en', request()->name_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['email'] =
            [
                'nullable','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('email', request()->email)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['username'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch){
                    return $query->where('username', request()->username)
//                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;

    }

    public function attributes()
    {
        return [
            "customer_category_id" => __('Customer Category'),
            "cars_number" => __('Cars Number'),
            "balance_to" => __('Balance To'),
            "balance_for" => __('Balance For'),
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
