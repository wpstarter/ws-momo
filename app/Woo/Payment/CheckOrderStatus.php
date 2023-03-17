<?php

namespace App\Woo\Payment;

use App\Jobs\UpdatePayments;

class CheckOrderStatus
{
    public function __construct()
    {
        add_action('woocommerce_thankyou',[$this,'checkOrderStatus']);
        add_action('wp_ajax_ws_order_status',[$this,'orderStatus']);
        add_action('wp_ajax_nopriv_ws_order_status',[$this,'orderStatus']);
        add_filter('woocommerce_valid_order_statuses_for_payment',[$this,'addStatus']);
    }
    function addStatus($status){
        $status[]='on-hold';
        return $status;
    }
    function orderStatus(){
        $order_id=ws_request('order_id');
        $order_key=ws_request('order_key');
        $status='pending';
        if($order_id && $order_key) {
            $order = wc_get_order($order_id);
            if($order_key===$order->get_order_key()){
                UpdatePayments::dispatch();
                $status=$order->get_status();
            }
        }
        wp_send_json_success(['status'=>$status]);

    }
    function checkOrderStatus($id){
        $order=wc_get_order($id);
        if($order) {
            if($order->needs_payment()) {
                add_action('wp_footer', function () use ($order) {
                    $this->renderCheckStatus($order);
                });
            }

        }
    }
    function renderCheckStatus(\WC_Order $order){
        echo ws_view('order.check-status', [
            'order_id' => $order->get_id(),
            'order_key'=>$order->get_order_key(),
            'status' =>$order->get_status(),
            'view_url'=>$order->get_checkout_order_received_url(),
        ]);
    }
}