<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class ReservationReq extends FormRequest
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
        $time_rule = '';
        return [
            'event_title' => 'required|max:100',
            'event_date' => 'required|after_or_equal:today',
            'event_time' => 'required'
        ];
    }

    function attributes() {
        return [
            'event_title' => __('reservations.event-title'),
            'event_date' => __('Date'),
            'event_time' => __('Time')
        ];
    }

    function messages() {
        return [
            'event_date.after_or_equal' => __('reservations.date-in-future')
        ];
    }
}
