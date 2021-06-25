<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['sales_invoice_terms_ar','sales_invoice_terms_en','sales_invoice_status',
        'maintenance_terms_ar','maintenance_terms_en','maintenance_status','branch_id','invoice_setting',
        'filter_setting','sell_from_invoice_status','lat','long','kilo_meter_price', 'quotation_terms_en',
        'quotation_terms_ar', 'quotation_terms_status'
    ];

    protected $table = 'settings';

    public function getSalesInvoiceTermsAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['sales_invoice_terms_ar'] : $this['sales_invoice_terms_en'];
    }

    public function getMaintenanceTermsAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['maintenance_terms_ar'] : $this['maintenance_terms_EN'];
    }

    public function getQuotationTermsAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['quotation_terms_ar'] : $this['quotation_terms_en'];
    }
}
