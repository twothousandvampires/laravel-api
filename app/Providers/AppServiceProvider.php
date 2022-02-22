<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application Services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application Services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();
    }
}
