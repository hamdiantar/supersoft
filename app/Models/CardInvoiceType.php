<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardInvoiceType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['card_invoice_id','maintenance_detection_id','type'];

    protected $table = 'card_invoice_types';

    public function items(){
        return $this->hasMany(CardInvoiceTypeItem::class, 'card_invoice_type_id');
    }

    public function maintenanceDetection(){
        return $this->belongsTo(MaintenanceDetection::class, 'maintenance_detection_id')->withTrashed();
    }

    public function cardInvoiceWinchRequest(){

        return $this->hasOne(CardInvoiceWinchRequest::class, 'card_invoice_type_id');
    }
}
