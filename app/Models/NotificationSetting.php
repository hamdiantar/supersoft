<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'branch_id',
        'customer_request',
        'quotation_request',
        'work_card_status_to_user',
        'minimum_parts_request',
        'end_work_card_employee',
        'end_residence_employee',
        'end_medical_insurance_employee',
        'quotation_request_status',
        'sales_invoice',
        'return_sales_invoice',
        'work_card',
        'work_card_status_to_customer',
        'sales_invoice_payments',
        'return_sales_invoice_payments',
        'work_card_payments',
        'follow_up_cars',
    ];

    protected $table = 'notification_settings';

}
