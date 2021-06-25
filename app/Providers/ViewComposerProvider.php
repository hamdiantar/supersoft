<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*' ,function($view) {
            $lang = app()->getLocale();
            $view->with('lang' ,$lang);
            $theme = 'dark-blue';
            if (auth()->check()) {
                $theme = auth()->user()->theme ?: $theme;
            } else if (auth()->guard('customer')->check()) {
                $theme = auth()->guard('customer')->user()->theme ?: $theme;
            }
            $view->with('theme' ,$theme);
        });
    }
}
