<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceItemReturn extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['sales_invoice_return_id','purchase_invoice_id','part_id','available_qty','return_qty','last_selling_price',
        'selling_price','discount_type','discount','sub_total','total_after_discount','total','sales_invoice_item_id'];

    protected $table = 'sales_invoice_item_returns';

    public function purchaseInvoice(){
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id')->withTrashed();
    }

    public function part(){
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function salesInvoiceReturn(){
        return $this->belongsTo(SalesInvoiceReturn::class, 'sales_invoice_return_id');
    }

    public function salesInvoiceItem(){
        return $this->belongsTo(SalesInvoiceItems::class, 'sales_invoice_item_id');
    }
}
