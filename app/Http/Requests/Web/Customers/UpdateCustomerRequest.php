<?php

namespace App\Http\Requests\Web\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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

            'city_id'=>'nullable|exists:cities,id',
            'country_id'=>'nullable|exists:countries,id',
            'area_id'=>'nullable|integer|exists:areas,id',
            'customer_category_id'=>'nullable|integer|exists:customer_categories,id',
            'type'=>'required|string|in:person,company',
            'status'=>'required|in:1,0',
            'cars_number'=>'required|numeric|min:0',
            'password'   =>'nullable|string|min:8',
            'address'   =>'nullable|string|max:200',
            'notes'=>'nullable|string|max:200',
            'phone1'=>'nullable|string|max:50',
            'phone2'=>'nullable|string|max:50',
            'tax_number'=>'nullable|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'maximum_fund_on' => 'required|numeric|min:0',
            'identity_number' => 'nullable',

            'contacts.*.phone_1' => 'required|string',
            'contactsUpdate.*.phone_1' => 'required|string',
            'contacts.*.phone_2' => 'nullable|string',
            'contacts.*.address' => 'nullable|string',
            'contacts.*.name' => 'required|string|min:1|max:100',
            'contactsUpdate.*.name' => 'required|string|min:1|max:100',

            'bankAccount.*.bank_name' => 'required',
            'bankAccount.*.account_number' => 'required',
            'bankAccountUpdate.*.bank_name' => 'required',
            'bankAccountUpdate.*.account_number' => 'required',
        ];

        if (request()->has('type') && request()->type == 'company'){

            $rules['fax']                  = 'nullable|integer|max:191';
            $rules['commercial_register']  = 'nullable|string|max:191';
            $rules['responsible']          = 'nullable|string|max:191';
            $rules['tax_card']             = 'nullable|string|max:191';
        }

        $branch = auth()->guard('customer')->user()->branch_id;

        $customer_id = auth()->guard('customer')->id();

        $rules['name_en'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch, $customer_id) {
                    return $query->where('id','!=', $customer_id);
                }),
            ];

        $rules['name_ar'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch, $customer_id){
                    return $query->where('id','!=', $customer_id)
                        ->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['email'] =
            [
                'nullable','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch, $customer_id){
                    return $query->where('id','!=', $customer_id)
                        ->where('email', request()->email)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['username'] =
            [
                'required','string','max:150',
                Rule::unique('customers')->where(function ($query) use($branch, $customer_id){
                    return $query->where('username', request()->username)
                        ->where('branch_id', $branch)
                        ->where('id','!=', $customer_id)
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
