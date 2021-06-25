<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CapitalBalance extends Model {

    protected $fillable = ['balance' ,'branch_id'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            if (auth()->check() && !authIsSuperAdmin()){
                $builder->where('branch_id', auth()->user()->branch_id);
            }
        });
    }

    function branch() {
        return $this->belongsTo(\App\Models\Branch::class ,'branch_id');
    }

    function editable() {
        $branch_id = $this->branch_id;
        $tables_check = [
            'advances' ,'employee_salaries' ,'sales_invoices' ,'sales_invoice_returns' ,'purchase_invoices' ,
            'purchase_returns' ,'expenses_receipts' ,'revenue_receipts' ,'work_cards'
        ];
        foreach($tables_check as $table) {
            $exists = \DB::table($table)->where('branch_id' ,$branch_id)->count();
            if ($exists > 0) return false;
        }
        return true;
    }

}