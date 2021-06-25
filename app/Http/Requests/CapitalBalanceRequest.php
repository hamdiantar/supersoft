<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapitalBalanceRequest extends FormRequest
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
        return [
            'balance' => 'required|numeric',
            'branch_id' => 'required|exists:branches,id'
        ];
    }

    function attributes() {
        return [
            'balance' => __('words.capital-balance')
        ];
    }
}
