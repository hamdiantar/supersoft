<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ExpensesReceiptObserver;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AccountingModule\Models\DailyRestriction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpensesReceipt extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'expenses_receipts';

    protected $fillable = [
        'date',
        'time',
        'receiver',
        'for',
        'cost',
        'deportation',
        'expense_type_id',
        'expense_item_id',
        'branch_id',
        'locker_id',
        'account_id',
        'purchase_invoice_id',
        'sales_invoice_return_id',
        'advance_id',
        'employee_salary_id',
        'number',
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

        self::observe(ExpensesReceiptObserver::class);
    }

    public function expenseItem(): BelongsTo
    {
        return $this->belongsTo(ExpensesItem::class ,'expense_item_id')->withTrashed();
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpensesType::class ,'expense_type_id')->withTrashed();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function salesInvoiceReturn(){
        return $this->belongsTo(SalesInvoiceReturn::class,'sales_invoice_return_id');
    }

    public function getExpensesNumberAttribute (){
        return  '##_'.$this->number ;
    }

    function locker() {
        return $this->belongsTo(Locker::class ,'locker_id');
    }

    function bank() {
        return $this->belongsTo(Account::class ,'account_id');
    }

    function salary() {
        return $this->belongsTo(EmployeeSalary::class ,'employee_salary_id');
    }

    function uneditable() {
        $conditions =
            $this->employee_salary_id || $this->advance_id || $this->sales_invoice_return_id || $this->purchase_invoice_id
            ?
                false
            :
                true;
        return $conditions;
    }

    static function deleteSelectedFromRestriction($ids) {
        ExpensesReceipt::whereIn('id' ,$ids)->orderBy('id' ,'desc')->chunk(100 ,function ($__d) {
            foreach($__d as $d) {
                $daily_restriction = DailyRestriction::where([
                    'reference_type' => '\App\Models\ExpensesReceipt',
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
