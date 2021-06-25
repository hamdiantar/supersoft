<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use App\Observers\RevenueReceiptObserver;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AccountingModule\Models\DailyRestriction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueReceipt extends Model
{
//    use SoftDeletes;

    use LogsActivity;

    protected $table = 'revenue_receipts';

    protected $fillable = [
        'date',
        'time',
        'receiver',
        'for',
        'cost',
        'deportation',
        'revenue_type_id',
        'revenue_item_id',
        'branch_id',
        'locker_id',
        'account_id',
        'purchase_return_id',
        'sales_invoice_id',
        'advance_id',
        'number',
        'card_invoice_id',
        'user_account_type',
        'user_account_id',
        'payment_type',
        'check_number',
        'bank_name',
        'cost_center_id'
    ];


    protected static $logAttributes = ['date','time','receiver'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());

        self::observe(RevenueReceiptObserver::class);
    }

    public function revenueItem(): BelongsTo
    {
        return $this->belongsTo(RevenueItem::class)->withTrashed();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function salesInvoice(){
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function cardInvoice(){
        return $this->belongsTo(CardInvoice::class, 'card_invoice_id');
    }

    public function getRevenueNumberAttribute (){
        return  '##_'.$this->number ;
    }

    function locker() {
        return $this->belongsTo(Locker::class ,'locker_id');
    }

    function bank() {
        return $this->belongsTo(Account::class ,'account_id');
    }

    function uneditable() {
        $conditions =
            $this->sales_invoice_id || $this->advance_id || $this->purchase_return_id
            ?
                false
            :
                true;
        return $conditions;
    }

    static function deleteSelectedFromRestriction($ids) {
        RevenueReceipt::whereIn('id' ,$ids)->orderBy('id' ,'desc')->chunk(100 ,function ($__d) {
            foreach($__d as $d) {
                $daily_restriction = DailyRestriction::where([
                    'reference_type' => '\App\Models\RevenueReceipt',
                    'reference_id' => $d->id
                ])->first();
                if ($daily_restriction) {
                    $daily_restriction->my_table()->delete();
                    $daily_restriction->delete();
                }
            }
        });
    }
}
