<?php

namespace App\Banking\Support;

class Payment
{
    public static function getRate(){
        $rate=intval(ws_setting('payment.rate'));
        $rate=$rate?:20000;
        return $rate;
    }
    public static function getAddFundsRate(){
        $rate=intval(ws_setting('payment.add_funds_rate'));
        $rate=$rate?:20000;
        return $rate;
    }
    public static function getInvoicePrefix(){
        return ws_setting('payment.invoice_prefix')?:'Ti';
    }
    public static function getAddFundsPrefix(){
        return ws_setting('payment.add_funds_prefix')?:'Nap';
    }

    public static function getPrefixes(){
        return [static::getInvoicePrefix(),static::getAddFundsPrefix()];
    }
}