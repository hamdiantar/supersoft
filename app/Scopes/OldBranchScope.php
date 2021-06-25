<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OldBranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(!authIsSuperAdmin() ){
            // This condition added by Ahmed Hesham to solve query issue when open reports
            if (in_array(\Route::currentRouteName() ,[
                'accounting-general-ledger' ,'trial-balance-index' ,
                'balance-sheet-index' ,'income-list-index' ,'adverse-restrictions-save'
            ])) return;

            if(auth()->check()){
                $builder->where('branch_id', auth()->user()->branch_id);
            }
            
        }
    }
}
