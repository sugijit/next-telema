<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminAllowMiddleware;
use App\Http\Middleware\NlAdminAllowMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('admin', AdminAllowMiddleware::class);
        $this->app['router']->aliasMiddleware('nl_admin', NlAdminAllowMiddleware::class);
    }
}
