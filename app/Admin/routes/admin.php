<?php

use WpStarter\Wordpress\Admin\Facades\Route;

Route::add('momo',\App\Admin\Controllers\MomoController::class)->position(30)->group(function(){
    Route::add('transactions',\App\Admin\Controllers\TransactionsController::class)
        ->resource()->position(30);
});

