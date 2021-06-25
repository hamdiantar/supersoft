<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
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
        $routes = [

            'web:login.form',
            'web:login',
            'web:logout',
            'web:register.form',
            'web:register',
            'web:login.facebook',
            'web:facebook.callback',
            'web:login.twitter',
            'web:twitter.callback',
            'web:login.google',
            'web:google.callback',

        ];

        if(!authIsSuperAdmin() && isset(request()->segments()[1]) && request()->segments()[1] == 'admin' && auth()->check()) {

            $builder->where('branch_id', auth()->user()->branch_id);
        }
    }
}
