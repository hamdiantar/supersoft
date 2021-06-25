<?php

namespace App\Http\Requests\Admin\ConcessionRelation;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'type' => 'required|string|in:add,withdrawal',
            'concession_type_id' => 'required|integer|exists:concession_types,id',
            'concession_item_id' => 'required|integer|exists:concession_type_items,id',
        ];
    }
}
