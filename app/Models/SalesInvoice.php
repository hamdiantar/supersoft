<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesInvoice extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['deleted_at'];

//    protected $appends = ['paid'];

    protected $fillable = ['invoice_number','customer_id','branch_id','created_by','date','time','type','number_of_items'
        ,'discount_type','discount','tax','sub_total','total_after_discount','total','customer_discount_status',
        'customer_discount','customer_discount_type', 'points_discount', 'points_rule_id'];

    protected $table = 'sales_invoices';

    protected static $logAttributes = ['invoice_number','created_by','type','discount_type','total_after_discount',
        'sub_total','total','customer_discount_status'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function created_by(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function items(){
        return $this->hasMany(SalesInvoiceItems::class, 'sales_invoice_id');
    }

    public function RevenueReceipts(){
        return $this->hasMany(RevenueReceipt::class, 'sales_invoice_id');
    }

    public function salesInvoiceReturn(){
        return $this->hasOne(SalesInvoiceReturn::class, 'sales_invoice_id');
    }

    public function getPaidAttribute(){
        return $this->RevenueReceipts->sum('cost');
    }

    public function getRemainingAttribute(){
        return $this->total - $this->RevenueReceipts->sum('cost');
    }

    public function getInvNumberAttribute(){
        return  '##_'.$this->invoice_number;
    }

    public function delete()
    {
        DB::transaction(function()
        {
            foreach ($this->items()->get() as $sales_item) {

                $part = $sales_item->part;

                if($part){
                    $part->quantity += $sales_item->sold_qty;
                    $part->save();
                }

                $purchase_invoice = $sales_item->purchaseInvoice;

                if($purchase_invoice){

                    $purchase_item = $purchase_invoice->items()->where('part_id', $sales_item->part_id)->first();

                    if($purchase_item){
                        $purchase_item->purchase_qty += $sales_item->sold_qty;
                        $purchase_item->save();
                    }
                }
            }

            $this->items()->delete();
            $this->removeBulkBalance($this->RevenueReceipts()->get());

            if( $this->RevenueReceipts ){

                $this->RevenueReceipts()->delete();
            }

            parent::delete();
        });
    }

    public function removeBulkBalance(Collection $collection)
    {
        foreach ($collection as $col) {
            if ($col->locker_id !== null) {
                $this->removeBalanceFromLocker($col);
            }
            if ($col->account_id !== null) {
                $this->removeBalanceFromAccount($col);
            }
        }
    }

    public function removeBalanceFromLocker(RevenueReceipt $receipt)
    {
        $locker = Locker::findOrFail($receipt->locker_id);
        $locker->update([
            'balance' => ($locker->balance  -  $receipt->cost)
        ]);
    }

    public function removeBalanceFromAccount(RevenueReceipt $receipt)
    {
        $account = Account::findOrFail($receipt->account_id);
        $account->update([
            'balance' => ($account->balance  - $receipt->cost)
        ]);
    }

    function scopePaid($query)
    {
        return $query->where('total','==', $this->paid);
    }

    function scopeRemaining($query)
    {
        return $query->where('total', '>', $this->paid);
    }

    function scopeGlobalCheck($query ,$request) {
        if ($request->has('global_check')) {
            $key = $request->global_check;

            $query->where('invoice_number' ,'like' ,"%$key%");

            $customers_ids = Customer::where(function ($__query) use($key) {
                $__query->orWhere('name_en' ,'like' ,'%'.$key.'%');
                $__query->orWhere('name_ar' ,'like' ,'%'.$key.'%');
                $__query->orWhere('phone1' ,'like' ,'%'.$key.'%');
                $__query->orWhere('phone2' ,'like' ,'%'.$key.'%');
            })->pluck('id')->toArray();

            if (!empty($customers_ids)) $query->orWhereIn('customer_id' ,$customers_ids);
        }
        return $query;
    }

    public function pointsLogs () {

        return $this->hasMany(PointLog::class, 'sales_invoice_id');
    }
}
