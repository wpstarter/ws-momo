<?php

namespace App\Banking\Model;

use WpStarter\Database\Eloquent\Model;

/**
 * @property $id
 * @property $tid
 * @property $bank
 * @property $content
 * @property $content_raw
 * @property $received_at
 * @property $notified_at
 * @property $status
 * @property $amount
 * @property $currency
 * @property $order_id
 * @property $prefix
 */
class BankTransaction extends Model
{
    protected $fillable=[
        'tid','bank','content','content_raw',
        'received_at','notified_at','status',
        'amount','currency','order_id','prefix',
    ];
}