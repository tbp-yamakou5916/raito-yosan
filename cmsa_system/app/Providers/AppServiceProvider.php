<?php

namespace App\Providers;

use App\View\AdminBaseComposer;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const USER_HOME = '';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 自社だけでエラー表示
        $allowedDebugIPs = ['150.249.207.176'];
        /*
        if (in_array(Request::ip(), $allowedDebugIPs)) {
            Config::set('app.debug', true);
        } else {
            Config::set('app.debug', false);
        }
        */

        // view composerの登録
        Facades\View::composer('admin.layouts.base', AdminBaseComposer::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      if (config('app.env') === 'production') {
        URL::forceScheme('https');
      }
    }
}
