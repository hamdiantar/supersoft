<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $fillable = [
        'branch_id',
        'customer_request_status',
        'customer_request_accept',
        'customer_request_reject',

        'quotation_request_status',
        'quotation_request_accept',
        'quotation_request_reject',
        'quotation_request_pending',

        'sales_invoice_status',
        'sales_invoice_create',
        'sales_invoice_edit',
        'sales_invoice_delete',

        'sales_invoice_return_status',
        'sales_invoice_return_create',
        'sales_invoice_return_edit',
        'sales_invoice_return_delete',

        'work_card_send_status',
        'work_card_create',
        'work_card_edit',
        'work_card_delete',

        'sales_invoice_payments_status',
        'sales_invoice_payments_create',
        'sales_invoice_payments_edit',
        'sales_invoice_payments_delete',

        'sales_return_payments_status',
        'sales_return_payments_create',
        'sales_return_payments_edit',
        'sales_return_payments_delete',

        'work_card_payments_status',
        'work_card_payments_create',
        'work_card_payments_edit',
        'work_card_payments_delete',

        'work_card_status',
        'work_card_status_pending',
        'work_card_status_processing',
        'work_card_status_finished',

        'car_follow_up_status',
        'car_follow_up_remember',

        'expenses_status',
        'expenses_create',
        'expenses_edit',
        'expenses_delete',

        'revenue_status',
        'revenue_create',
        'revenue_edit',
        'revenue_delete',
    ];

    protected $table = 'mail_settings';
}
