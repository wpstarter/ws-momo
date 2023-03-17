<?php

namespace App\Banking\Support;

class Invoice
{
    protected $order;
    protected $rate;
    protected $prefix;
    public function __construct(\WC_Order $order=null)
    {
        $this->order=$order;
        $this->rate=Payment::getRate();
        $this->prefix=Payment::getInvoicePrefix();
    }
    function getFormattedAmount(){
        return '<span>'.number_format($this->getAmount(),0,",",".").' <sup>'.get_woocommerce_currency_symbol('VND').'</sup></span>';
    }
    function getTotal(){
        if(is_null($this->order)){
            return WC()->cart->total;
        }
        return $this->order->get_total();
    }
    function getAmount(){
        return $this->getTotal()*$this->rate;
    }
    function getContent(){
        return $this->prefix.$this->order->get_id();
    }
    public static function forCart(){
        return new static();
    }
    public static function forOrder($order){
        if(!$order instanceof \WC_Order) {
            $order=wc_get_order($order);
            if(!$order){
                return static::forCart();
            }
        }
        return new static($order);
    }
}