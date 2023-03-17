<?php

namespace App\Banking;

use App\Banking\Api\BankApi;
use App\Banking\Api\Momo;
use App\Banking\Api\VpBank;
use WpStarter\Support\Manager;

/**
 *
 */
class ApiManager extends Manager
{

    public function getDefaultDriver()
    {
        return $this->config->get('banking.default');
    }

    function createMomoDriver(){
        return new Momo($this->config->get('banking.banks.momo',[]));
    }

    /**
     * @param $bank
     * @return BankApi
     */
    function bank($bank){
        return $this->driver($bank);
    }
}