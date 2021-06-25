<?php

namespace App\Http\Requests\Admin\CardInvoice;

use Illuminate\Foundation\Http\FormRequest;

class CreateCardInvoiceRequest extends FormRequest
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
           'work_card_id'=>'required|integer|exists:work_cards,id',
           'date'=>'required|date',
           'time'=>'required',
//           'type'=>'required|in:cash,credit',
           'terms'=>'nullable',
       ];

        $rules['maintenance_types'] = 'required';
        $rules['maintenance_types.*'] = 'required|integer|exists:maintenance_detection_types,id';

       if(request()->has('maintenance_types')){

           foreach(request()['maintenance_types'] as $type){
               $rules['type-'.$type] = 'required';

               if(request()->has('type-'.$type)) {
                   foreach (request()['type-' . $type]['maintenance_type_parts'] as $maintenance_type_part) {
                       $rules['notes_' . $maintenance_type_part] = 'nullable';
                       $rules['degree_' . $maintenance_type_part] = 'required';
                       $rules['image_' . $maintenance_type_part] = 'nullable';
                       $rules['image_' . $maintenance_type_part . '.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
                   }
               }
           }
       }
       return $rules;
    }
}
