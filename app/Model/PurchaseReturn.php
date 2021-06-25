<?php

namespace App\Model;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Locker;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\RevenueReceipt;
use App\Models\Supplier;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseReturn extends Model
{
    use LogsActivity;

    protected $table = 'purchase_returns';

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'branch_id',
        'purchase_invoice_id',
        'date',
        'time',
        'type',
        'number_of_items',
        'discount_type',
        'discount',
        'total',
        'total_after_discount',
        'paid',
        'remaining',
        'supplier_discount_status',
        'supplier_discount_type',
        'supplier_discount',
        'tax',
    ];


    protected static $logAttributes = ['invoice_number','date','time','number_of_items','total_after_discount',
        'is_discount_group_added'];

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

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    public function items(){
        return $this->hasMany(PurchaseReturnItem::class, 'purchase_returns_id');
    }

    public function revenueReceipt(): HasMany
    {
        return $this->hasMany(RevenueReceipt::class,'purchase_return_id');
    }

    public function getPaidAttribute(){
        return $this->revenueReceipt->sum('cost');
    }

    public function getRemainingAttribute(){
        return $this->total_after_discount - $this->revenueReceipt->sum('cost');
    }

    public function delete()
    {
        DB::transaction(function()
        {
            foreach ($this->items()->get() as $item) {

                $part = Part::find($item->part_id);

                if($part) {

                    $quantityInPart = $part->quantity;

                    $part->update([
                        'quantity' => $quantityInPart + $item->purchase_qty,
                    ]);
                }

                $purchaseInvoice = $this->invoice;

                if ($purchaseInvoice) {

                    $invoice_item = $purchaseInvoice->items()->where('part_id', $part->id)->first();

                    if ($invoice_item) {

                        $invoice_item->purchase_qty += $item->purchase_qty;
                        $invoice_item->save();
                    }
                }
            }

            $this->items()->delete();
            $this->removeBulkBalance($this->revenueReceipt()->get());
            $this->revenueReceipt()->delete();
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
        $locker = Locker::find($receipt->locker_id);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance  -  $receipt->cost)
            ]);
        }

    }

    public function removeBalanceFromAccount(RevenueReceipt $receipt)
    {
        $account = Account::find($receipt->account_id);
        if ($account) {
            $account->update([
                'balance' => ($account->balance  -  $receipt->cost)
            ]);
        }
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }
}
