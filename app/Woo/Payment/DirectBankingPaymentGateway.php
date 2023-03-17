<?php

namespace App\Woo\Payment;

use App\Banking\Support\Invoice;
use App\Banking\Support\Payment;
use WC_Order;

abstract class DirectBankingPaymentGateway extends \WC_Payment_Gateway
{
    protected $instructions;
    protected $account_details=[];


    abstract protected function getMethodTitle();
    abstract protected function getMethodDescription();
    public function __construct()
    {
        $this->icon               = apply_filters( 'ws_payment_'.$this->id.'_icon', '' );
        $this->has_fields         = false;
        $this->method_title       = $this->getMethodTitle();
        $this->method_description = $this->getMethodDescription();

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables.
        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->instructions = $this->get_option( 'instructions' );

        // BACS account fields shown on the thanks page and in emails.
        $this->account_details = [
            'bank_name'=>'',
            'bank_id'=>0,
            'account_number'=>'',
            'account_name'=>'',
        ];

        // Actions.
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_thankyou_'.$this->id, array( $this, 'thankyou_page' ) );

        // Customer Emails.
        add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled'         => array(
                'title'   => __( 'Enable/Disable', 'woocommerce' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable bank transfer', 'woocommerce' ),
                'default' => 'no',
            ),
            'title'           => array(
                'title'       => __( 'Title', 'woocommerce' ),
                'type'        => 'safe_text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                'default'     => __( 'Direct bank transfer', 'woocommerce' ),
                'desc_tip'    => true,
            ),
            'description'     => array(
                'title'       => __( 'Description', 'woocommerce' ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
                'default'     => __( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'woocommerce' ),
                'desc_tip'    => true,
            ),
            'instructions'    => array(
                'title'       => __( 'Instructions', 'woocommerce' ),
                'type'        => 'textarea',
                'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woocommerce' ),
                'default'     => '',
                'desc_tip'    => true,
            )
        );

    }
    /**
     * Process the payment and return the result.
     *
     * @param int $order_id Order ID.
     * @return array
     */
    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );

        if ( $order->get_total() > 0 ) {
            // Mark as on-hold (we're awaiting the payment).
            $order->update_status( apply_filters( 'woocommerce_bacs_process_payment_order_status', 'on-hold', $order ), __( 'Awaiting BACS payment', 'woocommerce' ) );
        } else {
            $order->payment_complete();
        }

        // Remove cart.
        WC()->cart->empty_cart();

        // Return thankyou redirect.
        return array(
            'result'   => 'success',
            'redirect' => $this->get_return_url( $order ),
        );

    }
    /**
     * Output for the order received page.
     *
     * @param int $order_id Order ID.
     */
    public function thankyou_page( $order_id ) {

        if ( $this->instructions ) {
            echo wp_kses_post( wpautop( wptexturize( wp_kses_post( $this->instructions ) ) ) );
        }
        add_filter('woocommerce_get_formatted_order_total',function($value, $order){
            $amount=Invoice::forOrder($order)->getFormattedAmount();
            $value.=' ('.$amount.')';
            return $value;
        },10,2);
        $this->bankDetails( $order_id );

    }

    /**
     * Add content to the WC emails.
     *
     * @param WC_Order $order Order object.
     * @param bool     $sent_to_admin Sent to admin.
     * @param bool     $plain_text Email format: plain text or HTML.
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

        if ( ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
            if ( $this->instructions ) {
                echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
            }
            $this->bankDetails( $order->get_id() );
        }

    }

    /**
     * Get bank details and place into a list format.
     *
     * @param int $order_id Order ID.
     */
    protected function bankDetails($order_id = '' ) {

        if ( empty( $this->account_details ) ) {
            return;
        }

        // Get order and store in $order.
        $order = wc_get_order( $order_id );

        if(!$order){
            return ;
        }
        $invoice=Invoice::forOrder($order);
        $data=[
            'gateway_id'=>$this->id,
            'bankName'=>$this->account_details['bank_name']??'',
            'accountNumber'=>$this->account_details['account_number']??'',
            'accountName'=>$this->account_details['account_name']??'',
            'amount'=>$invoice->getFormattedAmount(),
            'content'=>$invoice->getContent(),
            'order'=>$order,
            'qrUrl'=>$this->getQrCodeUrl($order),
        ];
        if($order->has_status('processing')){
            echo ws_view('bank.paid',$data);
            return ;
        }

        echo ws_view('bank.pending',$data);

    }
    protected function getQrCodeUrl($order){
        return '';
    }
    function payment_fields(){
        $description = $this->get_description();
        $invoice=Invoice::forOrder(absint( get_query_var( 'order-pay' ) ));
        $description=str_replace('{{total}}',$invoice->getFormattedAmount(),$description);
        if ( $description ) {
            echo wpautop( wptexturize( $description ) ); // @codingStandardsIgnoreLine.
        }
    }

}