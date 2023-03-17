<?php

namespace App\Banking\Api;

class Transaction
{
    public $id;
    public $order_id;
    public $prefix;
    public $bank;
    public $date;
    public $content;
    public $content_raw;
    public $amount;
    public $currency='VND';
}