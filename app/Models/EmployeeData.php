<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class EmployeeData extends Model
{
    use ColumnTranslation;

    protected $table = 'employee_data';

    protected $fillable = [
        'name_ar',
        'name_en',
        'Functional_class',
        'address',
        'phone1',
        'phone2',
        'id_number',
        'end_date_id_number',
        'start_date_assign',
        'start_date_stay',
        'end_date_stay',
        'end_date_health',
        'number_card_work',
        'status',
        'cv',
        'email',
        'notes',
        'rating',
        'employee_setting_id',
        'branch_id',
        'country_id',
        'city_id',
        'area_id',
        'national_id',
    ];
    protected static function boot() {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            if (auth()->check() && !authIsSuperAdmin()){
                $builder->where('branch_id', auth()->user()->branch_id);
            }
        });
    }

    public function employeeSetting(): BelongsTo
    {
        return $this->belongsTo(EmployeeSetting::class, 'employee_setting_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    function advances() {
        return $this->hasMany(Advances::class ,'employee_data_id');
    }

    function delays() {
        return $this->hasMany(EmployeeDelay::class ,'employee_data_id');
    }

    function rewards() {
        return $this->hasMany(EmployeeRewardDiscount::class ,'employee_data_id');
    }

    function absences() {
        return $this->hasMany(\App\Model\EmployeeAbsence::class ,'employee_id');
    }

    function salaries() {
        return $this->hasMany(EmployeeSalary::class ,'employee_id');
    }

    function revenue_receipts() {
        return $this->hasMany(RevenueReceipt::class ,'user_account_id')->where('user_account_type' ,'employees');
    }

    function expense_receipts() {
        return $this->hasMany(ExpensesReceipt::class ,'user_account_id')->where('user_account_type' ,'employees');
    }

    public function CardInvoiceTypeItems(){
        return $this->belongsToMany(CardInvoiceTypeItem::class, 'card_invoice_type_items_employee_data')
            ->withPivot('percent');
    }

    private function get_my_debit_balance() {
        $salaries = $this->salaries()->sum('rest_amount');
        $revenue_receipts = $this->revenue_receipts()->sum('cost');
        $deposit_advances = $this->advances()->where('operation' ,'deposit')->sum('amount');
        return $salaries + $revenue_receipts + $deposit_advances;
    }

    private function get_my_credit_balance() {
        $expense_receipts = $this->expense_receipts()->sum('cost');
        $deposit_advances = $this->advances()->where('operation' ,'withdrawal')->sum('amount');
        return $expense_receipts + $deposit_advances;
    }

    function direct_balance() {
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

    function get_my_balance() {
        $debit = $this->get_my_debit_balance();
        $credit = $this->get_my_credit_balance();
        $msg = __("Balance For Employee");
        $balance = $debit - $credit;
        if ($credit > $debit) {
            $balance = $credit - $debit;
            $msg = __("Balance On Employee");
        }
        return ['balance_title' => $msg ,'balance' => $balance];
    }

    function my_allowed_vacations() {
        return $this->employeeSetting->annual_vocation_days - $this->absences()->where('absence_type' ,'vacation')->sum('absence_days');
    }
}

/**
 * balance for employee : revenueReceipt "direct" + advances "deposit" + salary "rest amount"
 * balance on employee : expenseReceipt "direct" + advances "withdraw"
 */