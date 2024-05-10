<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Services\Log;

class LogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Log::class, function (Application $app) {
            return new Log();
        });
    }

}
