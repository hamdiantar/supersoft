<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardInvoice extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['invoice_number', 'work_card_id', 'created_by', 'date', 'time', 'type', 'terms'
        , 'discount_type', 'discount', 'tax', 'sub_total', 'total_after_discount', 'total', 'customer_discount'
        , 'customer_discount_type', 'customer_discount_status', 'points_discount', 'points_rule_id'];

    protected $table = 'card_invoices';

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function maintenanceDetectionTypes()
    {
        return $this->belongsToMany(MaintenanceDetectionType::class, 'card_invoice_maintenance_types',
            'card_invoice_id', 'maintenance_type_id')
            ->withPivot('sub_total', 'discount_type', 'discount', 'total_after_discount');
    }

    public function maintenanceDetections()
    {
        return $this->belongsToMany(MaintenanceDetection::class, 'card_invoice_maintenance_detection')
            ->withPivot('notes', 'images', 'degree', 'maintenance_type_id');
    }

    public function workCard()
    {
        return $this->belongsTo(WorkCard::class, 'work_card_id')->withTrashed();
    }

    public function RevenueReceipts()
    {
        return $this->hasMany(RevenueReceipt::class, 'card_invoice_id');
    }

    public function getPaidAttribute()
    {
        return $this->RevenueReceipts->sum('cost');
    }

    public function getRemainingAttribute()
    {
        return $this->total - $this->RevenueReceipts->sum('cost');
    }

    public function types()
    {
        return $this->hasMany(CardInvoiceType::class, 'card_invoice_id');
    }

    public function getInvNumberAttribute()
    {
        return '##_' . $this->invoice_number;
    }

    public function pointsLogs () {

        return $this->hasMany(PointLog::class, 'card_invoice_id');
    }
}
