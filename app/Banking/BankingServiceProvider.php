<?php

namespace App\Banking;

use Illuminate\Support\ServiceProvider;

class BankingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ApiManager::class);
        $this->app->alias(ApiManager::class,'banking.api');
    }
}