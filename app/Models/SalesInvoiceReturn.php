<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesInvoiceReturn extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['deleted_at'];

    protected $fillable = ['invoice_number','sales_invoice_id','branch_id','created_by','date','time','type','number_of_items'
        ,'discount_type','discount','tax','sub_total','total_after_discount','total','customer_id','customer_discount_status'
        ,'customer_discount','customer_discount_type', 'points_discount', 'points_rule_id'];

    protected $table = 'sales_invoice_returns';

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

    public function salesInvoice(){
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id')->withTrashed();
    }

    public function getInvNumberAttribute(){
        return  '##_'.$this->invoice_number;
    }

    public function expensesReceipts(){
        return $this->hasMany(ExpensesReceipt::class,'sales_invoice_return_id');
    }

    public function getPaidAttribute(){
        return $this->expensesReceipts->sum('cost');
    }

    public function getRemainingAttribute(){
        return $this->total - $this->expensesReceipts->sum('cost');
    }

    public function items(){
        return $this->hasMany(SalesInvoiceItemReturn::class, 'sales_invoice_return_id');
    }

    public function delete()
    {
        DB::transaction(function()
        {
            foreach ($this->items()->get() as $sales_item) {

                $part = $sales_item->part;

                if($part){
                    $part->quantity -= $sales_item->return_qty;
                    $part->save();
                }

                $purchase_invoice = $sales_item->purchaseInvoice;

                if($purchase_invoice){

                    $purchase_item = $purchase_invoice->items()->where('part_id', $sales_item->part_id)->first();

                    if($purchase_item){
                        $purchase_item->purchase_qty -= $sales_item->return_qty;
                        $purchase_item->save();
                    }
                }
            }

            $this->items()->delete();
            $this->removeBulkBalance($this->expensesReceipts()->get());

            if($this->expensesReceipts){

                $this->expensesReceipts()->delete();
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

    public function removeBalanceFromLocker(ExpensesReceipt $receipt)
    {
        $locker = Locker::findOrFail($receipt->locker_id);
        $locker->update([
            'balance' => ($locker->balance  +  $receipt->cost)
        ]);
    }

    public function removeBalanceFromAccount(ExpensesReceipt $receipt)
    {
        $account = Account::findOrFail($receipt->account_id);
        $account->update([
            'balance' => ($account->balance  + $receipt->cost)
        ]);
    }

    public function pointsLogs () {

        return $this->hasMany(PointLog::class, 'sales_invoice_return_id');
    }

}
