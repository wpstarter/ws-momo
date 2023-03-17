<?php

namespace App\Woo\Payment;

class PaymentGatewaysManager
{
    function __construct()
    {
        add_filter('woocommerce_payment_gateways', [$this, 'addPaymentGateway']);
        add_filter('woocommerce_available_payment_gateways',[$this,'availablePaymentGateways']);
    }
    function addPaymentGateway($gateways){
        $gateways[]=MomoPaymentGateway::class;
        return $gateways;
    }
    function availablePaymentGateways($gateways){
        $customer=WC()->customer;

        return $gateways;
    }
    function getCountry($customer){
        $location = \WC_Geolocation::geolocate_ip();
        $country = $location['country'];
        if($country=='VN'){
            return $country;
        }
        return $customer->get_billing_country();
    }
}