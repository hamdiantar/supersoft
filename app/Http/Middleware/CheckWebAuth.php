<?php

namespace App\Http\Middleware;

use Closure;

class CheckWebAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::guard('customer')->check()) {

            return redirect(route('web:login.form'));
        }

        return $next($request);
    }
}
