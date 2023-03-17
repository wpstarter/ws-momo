<?php

namespace App\Banking\Api;

use App\Banking\Support\Payment;

abstract class BankApi
{
    protected $config;
    protected $prefixes;
    protected $hasNewTransaction=false;
    public function __construct($config)
    {
        $this->config=$config;
    }
    public function setHasNewTransaction($status=true){
        $this->hasNewTransaction=$status;
        return $this;
    }
    public function gotNewTransaction($transaction){

    }
    public function transactionsUpdated(){

    }
    protected function getPrefixes(){
        if(!$this->prefixes){
            $this->prefixes=Payment::getPrefixes();
        }
        return $this->prefixes;
    }

    protected function getDefaultHeaders(){
        return [
            'Accept'=>'application/json',
            'Accept-Language'=>'vi-VN,vi;q=0.9,en;q=0.8,en-US;q=0.7,fr;q=0.6,fr-FR;q=0.5',
            'Content-Type'=>'application/json',
            'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
            'X-Requested-With'=>'XMLHttpRequest',

        ];
    }

    /**
     * @return Transaction[]
     */
    abstract function getTransactions();
}