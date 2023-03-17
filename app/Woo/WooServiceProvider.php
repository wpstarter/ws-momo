<?php

namespace App\Woo;

use App\Woo\Payment\CheckOrderStatus;
use App\Woo\Payment\PaymentGatewaysManager;
use WpStarter\Support\ServiceProvider;

class WooServiceProvider extends ServiceProvider
{
    function register()
    {
        $this->app->singleton(PaymentGatewaysManager::class);
    }

    function boot(){
        if(!function_exists('add_filter')){
            return ;
        }
        $this->app->make(PaymentGatewaysManager::class);
        $this->app->make(CheckOrderStatus::class);
    }
}