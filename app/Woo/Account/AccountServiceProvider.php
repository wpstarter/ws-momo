<?php

namespace App\Woo\Account;

use App\Woo\Account\Controllers\AccountFundsController;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    function boot(){
        if(is_wp()) {
            $this->app->make(AccountFundsController::class);
        }
    }
}