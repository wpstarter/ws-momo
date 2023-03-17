<?php

namespace App\Woo\Payment;

use App\Banking\Support\Invoice;
use App\Banking\Support\Payment;
use WC_Order;
use WC_Payment_Gateway;
use WpStarter\Validation\Rules\In;

class MomoPaymentGateway extends WC_Payment_Gateway
{

    /**
     * Constructor for the gateway.
     */
    public function __construct() {

        $this->id                 = 'direct_bank_transfer';

    }

    public function getQrCodeUrl($order)
    {
        $invoice=Invoice::forOrder($order);
        $params=[
            'bankcode'=>$this->account_details['bank_id'],
            'account'=>$this->account_details['account_number'],
            'amount'=>$invoice->getAmount(),
            'noidung'=>$invoice->getContent(),
        ];
        $url=site_url('/vietqr.php');
        return add_query_arg($params,$url);
    }

}