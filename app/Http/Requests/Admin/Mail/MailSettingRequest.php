<?php

namespace App\Http\Requests\Admin\Mail;

use Illuminate\Foundation\Http\FormRequest;

class MailSettingRequest extends FormRequest
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

        $rules = [];

        if (request()->has('customer_request_status')) {

            $rules['customer_request_accept'] = 'required|string';
            $rules['customer_request_reject'] = 'required|string';
        }

        if (request()->has('quotation_request_status')) {

            $rules['quotation_request_pending'] = 'required|string';
            $rules['quotation_request_accept'] = 'required|string';
            $rules['quotation_request_reject'] = 'required|string';
        }

        if (request()->has('sales_invoice_status')) {

            $rules['sales_invoice_create'] = 'required|string';
            $rules['sales_invoice_edit'] = 'required|string';
            $rules['sales_invoice_delete'] = 'required|string';
        }

        if (request()->has('sales_invoice_return_status')) {

            $rules['sales_invoice_return_create'] = 'required|string';
            $rules['sales_invoice_return_edit'] = 'required|string';
            $rules['sales_invoice_return_delete'] = 'required|string';
        }

        if (request()->has('work_card_send_status')) {

            $rules['work_card_create'] = 'required|string';
            $rules['work_card_edit'] = 'required|string';
            $rules['work_card_delete'] = 'required|string';
        }

        if (request()->has('sales_invoice_payments_status')) {

            $rules['sales_invoice_payments_create'] = 'required|string';
            $rules['sales_invoice_payments_edit'] = 'required|string';
            $rules['sales_invoice_payments_delete'] = 'required|string';
        }

        if (request()->has('sales_invoice_payments_status')) {

            $rules['sales_invoice_payments_create'] = 'required|string';
            $rules['sales_invoice_payments_edit'] = 'required|string';
            $rules['sales_invoice_payments_delete'] = 'required|string';
        }

        if (request()->has('sales_return_payments_status')) {

            $rules['sales_return_payments_create'] = 'required|string';
            $rules['sales_return_payments_edit'] = 'required|string';
            $rules['sales_return_payments_delete'] = 'required|string';
        }

        if (request()->has('work_card_payments_status')) {

            $rules['work_card_payments_create'] = 'required|string';
            $rules['work_card_payments_edit'] = 'required|string';
            $rules['work_card_payments_delete'] = 'required|string';
        }

        if (request()->has('work_card_status')) {

            $rules['work_card_status_pending'] = 'required|string';
            $rules['work_card_status_processing'] = 'required|string';
            $rules['work_card_status_finished'] = 'required|string';
        }

        if (request()->has('car_follow_up_status')) {

            $rules['car_follow_up_remember'] = 'required|string';
        }

        if (request()->has('expenses_status')) {

            $rules['expenses_create'] = 'required|string';
            $rules['expenses_edit'] = 'required|string';
            $rules['expenses_delete'] = 'required|string';
        }

        if (request()->has('revenue_status')) {

            $rules['revenue_create'] = 'required|string';
            $rules['revenue_edit'] = 'required|string';
            $rules['revenue_delete'] = 'required|string';
        }

        return $rules;
    }
}
