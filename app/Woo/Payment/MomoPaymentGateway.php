<?php

namespace App\Woo\Payment;

use App\Banking\Api\Momo\MomoApi;
use App\Banking\Support\Invoice;
use App\Banking\Support\Payment;
use WC_Order;
use WC_Payment_Gateway;
use WpStarter\Validation\Rules\In;

class MomoPaymentGateway extends DirectBankingPaymentGateway
{

    /**
     * Constructor for the gateway.
     */
    public function __construct() {

        $this->id                 = 'momo_payment_gateway';
        parent::__construct();
        $this->account_details['bank_name']='Momo';
        $this->account_details['account_name']=MomoApi::make()->config()->name;
        $this->account_details['account_number']=MomoApi::make()->config()->phone;


    }
    public function is_available()
    {
        if(!MomoApi::make()->config()->phone){
            return false;
        }
        return parent::is_available();
    }

    protected function getMethodTitle()
    {
        return 'Thanh toán qua Momo';
    }

    protected function getMethodDescription()
    {
        return 'Nhận chuyển tiền qua Momo và tự động kích hoạt order';
    }

    public function getQrCodeUrl($order)
    {
        $invoice=Invoice::forOrder($order);
        $momoStr=sprintf("2|99|%s|%s||0|0|%s|%s|transfer_myqr",
            $this->account_details['account_number'],
            $this->account_details['account_name'],
            intval($invoice->getAmount()),
            $invoice->getContent(),
        );
        $url='https://chart.googleapis.com/chart?cht=qr&choe=UTF-8&chs=400';
        return add_query_arg(['chl'=>$momoStr],$url);
    }


}