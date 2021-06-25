<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['name_ar', 'name_en'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'name_en',
        'name_ar',
        'branch_id',
        'status',
        'country_id',
        'city_id',
        'area_id',
        'phone_1',
        'phone_2',
        'address',
        'type',
        'email',
        'fax',
        'commercial_number',
        'tax_card',
        'funds_for',
        'funds_on',
        'description',
        'tax_number',
        'lat',
        'long',
        'maximum_fund_on',
        'library_path',
        'group_id',
        'sub_group_id',
        'supplier_type',
        'identity_number',
    ];

    protected $table = 'suppliers';

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }

    public function getActiveAttribute()
    {
        return $this->status == 1 ? __('Active') : __('inActive');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function suppliersGroup()
    {
        return $this->belongsTo(SupplierGroup::class, 'group_id')->withTrashed();
    }

    function revenue_receipts()
    {
        return $this->hasMany(RevenueReceipt::class, 'user_account_id')->where('user_account_type', 'suppliers');
    }

    function expense_receipts()
    {
        return $this->hasMany(ExpensesReceipt::class, 'user_account_id')->where('user_account_type', 'suppliers');
    }

    function buy_invoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'supplier_id');
    }

    function return_buy_invoices()
    {
        return $this->hasMany(\App\Model\PurchaseReturn::class, 'supplier_id');
    }

    function contacts()
    {
        return $this->hasMany(SupplierContact::class, 'supplier_id');
    }

    private function get_my_debit_balance()
    {
        $buy_invoices = $this->buy_invoices()->sum('remaining');
        $revenue_receipts = $this->revenue_receipts()->sum('cost');
        return $buy_invoices + $revenue_receipts;
    }

    private function get_my_credit_balance()
    {
        $expense_receipts = $this->expense_receipts()->sum('cost');
        $return_buy_invoices = $this->return_buy_invoices()->sum('remaining');
        return $expense_receipts + $return_buy_invoices;
    }

    function direct_balance()
    {
        $debit = $this->get_my_debit_balance();
        $credit = $this->get_my_credit_balance();
        if ($debit - $credit >= 0) {
            $debit = $debit - $credit;
            $credit = 0;
        } else {
            $credit = $credit - $debit;
            $debit = 0;
        }
        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    function get_my_balance()
    {
        $debit = $this->get_my_debit_balance();
        $credit = $this->get_my_credit_balance();
        $msg = __("Balance For Supplier");
        $balance = $debit - $credit;
        if ($credit > $debit) {
            $balance = $credit - $debit;
            $msg = __("Balance On Supplier");
        }
        return ['balance_title' => $msg, 'balance' => $balance];
    }

    public function files()
    {
        return $this->hasMany(SupplierLibrary::class, 'supplier_id');
    }

    public function setMainGroupsIdAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['main_groups_id'] = serialize($value);
        } else {
            $this->attributes['main_groups_id'] = $value;
        }
    }

    public function setSubGroupsIdAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['sub_groups_id'] = serialize($value);
        } else {
            $this->attributes['sub_groups_id'] = $value;
        }
    }

    public function getMainGroupsIdAttribute($value)
    {
        $ids = (!is_null($value) && !is_null(unserialize($value))) ? unserialize($value) : [];
        return SupplierGroup::whereIn('id', $ids)->get();
    }

    public function getSubGroupsIdAttribute($value)
    {
        $ids = (!is_null($value) && !is_null(unserialize($value))) ? unserialize($value) : [];
        return SupplierGroup::whereIn('id', $ids)->get();
    }

    public function getSupplierTypeAttribute($value)
    {
        return __($value);
    }

    function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'supplier_id');
    }

    public function group()
    {
        return $this->belongsTo(SupplierGroup::class, 'group_id')->withTrashed();
    }

    public function subGroup()
    {
        return $this->belongsTo(SupplierGroup::class, 'sub_group_id');
    }

    public function getGroupDiscountAttribute () {

        if ($this->subGroup && $this->subGroup->status == 1) {

            return $this->subGroup->discount;

        }elseif ($this->group && $this->group->status == 1) {

            return $this->group->discount;

        }else {

            return 0;
        }
    }

    public function getGroupDiscountTypeAttribute () {

        if ($this->subGroup && $this->subGroup->status == 1) {

            return $this->subGroup->discount_type;

        }elseif ($this->group && $this->group->status == 1) {

            return $this->group->discount_type;

        }else {

            return 'amount';
        }
    }
}
