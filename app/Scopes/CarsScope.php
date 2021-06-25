<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CarsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(!authIsSuperAdmin() && isset(request()->segments()[1]) && request()->segments()[1] == 'admin'){

//            if (in_array(\Route::currentRouteName() ,[
//
//                'web:login.form',
//                'web:login',
//                'web:logout',
//                'web:register.form',
//                'web:register',
//                'web:login.facebook',
//                'web:facebook.callback',
//                'web:login.twitter',
//                'web:twitter.callback',
//                'web:login.google',
//                'web:google.callback',
//
//            ])) return;


            $builder->whereHas('customer', function ($q){
                $q->where('branch_id', auth()->user()->branch_id);
            });
        }
    }

}
