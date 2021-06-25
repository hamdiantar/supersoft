<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceItems extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['sales_invoice_id','purchase_invoice_id','part_id','available_qty','sold_qty','last_selling_price',
        'selling_price','discount_type','discount','sub_total','total_after_discount','total'];

    protected $table = 'sales_invoice_items';

    public function purchaseInvoice(){
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id')->withTrashed();
    }

    public function part(){
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }
}
