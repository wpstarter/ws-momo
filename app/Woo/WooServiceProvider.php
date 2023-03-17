<?php

namespace App\Woo;

use App\Woo\Account\AccountServiceProvider;
use App\Woo\Payment\PaymentGatewaysManager;
use App\Woo\Payment\ShowVnPrice;
use WpStarter\Support\ServiceProvider;

class WooServiceProvider extends ServiceProvider
{
    function register()
    {
        $this->app->singleton(PaymentGatewaysManager::class);
        $this->app->register(AccountServiceProvider::class);
    }

    function boot(){
        if(!function_exists('add_filter')){
            return ;
        }
        $this->app->make(PaymentGatewaysManager::class);
        $this->app->make(ShowVnPrice::class);
    }
}