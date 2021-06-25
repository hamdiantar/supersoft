<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardInvoiceTypeItem extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable =
        [
            'card_invoice_type_id','model_id','purchase_invoice_id', 'qty','price','discount_type','discount',
            'sub_total','total_after_discount'
        ];

    protected $table = 'card_invoice_type_items';

    public function part(){
        return $this->belongsTo(Part::class, 'model_id')->withTrashed();
    }

    public function service(){
        return $this->belongsTo(Service::class, 'model_id')->withTrashed();
    }

    public function package(){
        return $this->belongsTo(ServicePackage::class, 'model_id')->withTrashed();
    }

    public function purchaseInvoice(){
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id')->withTrashed();
    }

    public function employees(){
        return $this->belongsToMany(EmployeeData::class, 'card_invoice_type_items_employee_data',
            'item_id','employee_id')
            ->withPivot('percent');
    }
}
