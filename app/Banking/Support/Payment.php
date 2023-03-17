<?php

namespace App\Banking\Support;

class Payment
{
    public static function getRate(){
        $rate=intval(ws_config('banking.rate'));
        $rate=$rate?:20000;
        if(get_woocommerce_currency()==='VND'){
            return 1;
        }
        return $rate;
    }
    public static function getAddFundsRate(){
        $rate=intval(ws_config('banking.add_funds_rate'));
        $rate=$rate?:20000;
        return $rate;
    }
    public static function getInvoicePrefix(){
        return ws_config('banking.prefix.invoice')?:'Ti';
    }
    public static function getAddFundsPrefix(){
        return ws_config('banking.prefix.add_funds_prefix')?:'Nap';
    }

    public static function getPrefixes(){
        return ws_config('banking.prefix');
    }
}