<?php

namespace App\Http\Requests\Admin\RevenueItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRevenueItemRequest extends FormRequest
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

//            'item_ar' => 'required|string|max:50',
//            'item_en' => 'required|string|max:50',
//            'branch_id' => 'required|exists:branches,id',
            'revenue_id' => 'required|exists:revenue_types,id',
        ];

        if(authIsSuperAdmin()){

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        $rules['item_en'] =
            [
                'required','string','max:150',
                Rule::unique('revenue_items')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->revenueItem->id)
                        ->where('item_en', request()->item_en)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['item_ar'] =
            [
                'required','string','max:150',
                Rule::unique('revenue_items')->where(function ($query) use($branch){
                    return $query->where('id','!=', $this->revenueItem->id)
                        ->where('item_ar', request()->item_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;

    }

    public function attributes()
    {
        return [
            'item_ar' => __('Item in Arabic'),
            'item_en' => __('Item in English'),
            'branch_id' => __('Branch'),
            'revenue_id' => __('Revenue Type')
        ];
    }
}
