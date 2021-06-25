<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationTypeItem extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['quotation_type_id','model_id','purchase_invoice_id','qty','price','discount_type','discount',
        'sub_total','total_after_discount'];

    protected $table = 'quotation_type_items';

    public function part(){
        return $this->belongsTo(Part::class, 'model_id');
    }
    public function service(){
        return $this->belongsTo(Service::class, 'model_id');
    }

    public function package(){
        return $this->belongsTo(ServicePackage::class, 'model_id');
    }

    public function purchaseInvoice(){
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

}
