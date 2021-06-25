<?php

namespace App\Models;

use App\Model\CustomerReservation;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use LogsActivity, SoftDeletes, Notifiable;
    /**
     * @var string
     */

    protected $guarded = 'customer';

    protected $table = 'customers';

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'name_ar',
        'name_en',
        'address',
        'phone1',
        'phone2',
        'email',
        'status',
        'notes',
        'fax',
        'commercial_register',
        'cars_number',
        'balance_to',
        'balance_for',
        'tax_card',
        'responsible',
        'type',
        'branch_id',
        'country_id',
        'country_id',
        'city_id',
        'customer_category_id',
        'area_id',
        'username',
        'password',
        'can_edit',
        'provider',
        'theme',
        'points',
        'tax_number',
        'lat',
        'long',
        'maximum_fund_on',
        'library_path',
        'identity_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function customerCategory(): BelongsTo
    {
        return $this->belongsTo(CustomerCategory::class, 'customer_category_id', 'id')->withTrashed();
    }
    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }

    function revenue_receipts() {
        return $this->hasMany(RevenueReceipt::class ,'user_account_id')->where('user_account_type' ,'customers');
    }

    function expense_receipts() {
        return $this->hasMany(ExpensesReceipt::class ,'user_account_id')->where('user_account_type' ,'customers');
    }

    function sale_invoices() {
        return $this->hasMany(SalesInvoice::class ,'customer_id');
    }

    function return_sale_invoices() {
        return $this->hasMany(SalesInvoiceReturn::class ,'customer_id');
    }

    function work_card() {
        return $this->hasMany(WorkCard::class ,'customer_id');
    }

    public function country () {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city () {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area () {
        return $this->belongsTo(Area::class, 'area_id');
    }

    function reservations() {
        return $this->hasMany(CustomerReservation::class ,'customer_id');
    }

    function pointsLogs() {
        return $this->hasMany(PointLog::class ,'customer_id');
    }

    function files() {

        return $this->hasMany(CustomerLibrary::class ,'customer_id');
    }

    function contacts() {
        return $this->hasMany(CustomerContact::class ,'customer_id');
    }

    private function get_my_debit_balance() {
        $total_remainging = 0;

        $this->sale_invoices()
            ->orderBy('id' ,'desc')
            // ->where('type' ,'credit')
            ->chunk(100 ,function ($invoices) use (&$total_remainging) {
                foreach($invoices as $inv) {
                    $total_paid = RevenueReceipt::where('sales_invoice_id' ,$inv->id)->sum('cost');
                    $total_remainging += $inv->total - $total_paid;
                }
            });
        $sale_invoices = $total_remainging;
        $revenue_receipts = $this->revenue_receipts()->sum('cost');
        return ($sale_invoices) + $revenue_receipts;
    }

    private function get_my_credit_balance() {
        $total_remainging = 0;
        $this->return_sale_invoices()
            ->orderBy('id' ,'desc')
            // ->where('type' ,'credit')
            ->chunk(100 ,function ($invoices) use (&$total_remainging) {
                foreach($invoices as $inv) {
                    $total_paid = ExpensesReceipt::where('sales_invoice_return_id' ,$inv->id)->sum('cost');
                    $total_remainging += $inv->total - $total_paid;
                }
            });
        $return_sale_invoices = $total_remainging;
        $expense_receipts = $this->expense_receipts()->sum('cost');
        $car_maintenance_rest_amount = 0;
        WorkCard
            ::join('card_invoices' ,'work_cards.id' ,'=' ,'card_invoices.work_card_id')
            ->where('card_invoices.type' ,'credit')
            ->where('work_cards.customer_id' ,$this->id)
            ->select(
                'card_invoices.id as invoice_id',
                'card_invoices.total_after_discount as total'
            )
            ->orderBy('work_cards.id' ,'desc')
            ->chunk(50 ,function ($cards) use (&$car_maintenance_rest_amount) {
                foreach($cards as $card) {
                    $total_paid = RevenueReceipt::where('card_invoice_id' ,$card->invoice_id)->sum('cost');
                    $car_maintenance_rest_amount += ($card->total - $total_paid);
                }
            });
        return $expense_receipts + $return_sale_invoices + $car_maintenance_rest_amount;
    }

    function direct_balance() {
        $debit = $this->get_my_debit_balance();
        $credit = $this->get_my_credit_balance();
        if ($debit - $credit >= 0) {
            $debit -= $credit;
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

    function get_my_balance() {
        $balance = $this->direct_balance();
        $debit = $balance['debit'];
        $credit = $balance['credit'];
        if ($credit > 0)
            return ['balance_title' => __("Balance On Customer") ,'balance' => $credit];
        else
            return ['balance_title' => __("Balance For Customer") ,'balance' => $debit];
    }

    function scopeGlobalCheck($query ,$request) {
        if ($request->has('global_check') && $request->global_check != '') {
            $query->orWhere('name_en' ,'like' ,'%'.$request->global_check.'%');
            $query->orWhere('name_ar' ,'like' ,'%'.$request->global_check.'%');
            $query->orWhere('phone1' ,'like' ,'%'.$request->global_check.'%');
            $query->orWhere('phone2' ,'like' ,'%'.$request->global_check.'%');

            $customers_ids = Car::where('plate_number' ,'like' ,'%'.$request->global_check.'%')->pluck('customer_id')->toArray();
            if (!empty($customers_ids)) $query->orWhereIn('id' ,$customers_ids);
        }
        return $query;
    }

    function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'customer_id');
    }
}

/**
 * balance for supplier : revenueReceipt "direct" + sale invoices "rest amount"
 * balance on supplier : expenseReceipt "direct" + sale return invoice "rest amount" + car maintenance rest amount
 */
