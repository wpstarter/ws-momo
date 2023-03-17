<?php

namespace App\Woo\Account\Controllers;


use App\Models\UserTransaction;

class AccountFundsController
{
    public function __construct()
    {
        $this->hooks();
    }
    function hooks(){
        add_filter('woocommerce_account_menu_items', [$this, 'accountMenuItems']);
        add_filter('woocommerce_get_query_vars', [$this, 'queryVars']);
        add_action('woocommerce_account_transactions_endpoint', [$this, 'setupPage']);
        add_filter('woocommerce_endpoint_transactions_title', [$this, 'pageTitle']);
        add_action('woocommerce_settings_pages', [$this, 'endpointOption']);
        add_action('flatsome_after_account_user',function(){
            echo ws_view('account.fund',['user'=>ws_auth()->user()]);
        });
    }


    function pageTitle()
    {
        return __('Transactions', 'ti_shop');
    }

    function setupPage()
    {
        $transactions=UserTransaction::query()->where('user_id',get_current_user_id())
            ->orderBy('created_at','desc')->paginate();
        echo ws_view('account.transactions',['transactions'=>$transactions]);
    }

    function queryVars($queries)
    {
        $queries['transactions'] = get_option('woocommerce_myaccount_transactions_endpoint', 'transactions');
        return $queries;
    }
    function accountMenuItems($items)
    {
        $keys = array_keys($items);
        $values = array_values($items);
        $pos = count($keys);
        foreach ($keys as $pos => $key) {
            if ($key === 'downloads') {
                $pos++;
                break;
            }
        }
        array_splice($keys, $pos, 0, 'transactions');
        array_splice($values, $pos, 0, __('Transactions', 'ti_shop'));
        $items = array_combine($keys, $values);
        unset($items['downloads']);
        return $items;
    }
    function endpointOption($fields)
    {
        $pos = 0;
        foreach ($fields as $field) {
            if ($field['id'] === 'woocommerce_myaccount_downloads_endpoint') {
                break;
            }
            $pos++;
        }
        if (isset($pos)) {
            array_splice($fields, $pos + 1, 0, [[
                'title' => __('Transactions', 'woocommerce'),
                'desc' => __('Endpoint for the "My account &rarr; Transactions" page.', 'woocommerce'),
                'id' => 'woocommerce_myaccount_transactions_endpoint',
                'type' => 'text',
                'default' => 'transactions',
                'desc_tip' => true,
            ]]);
        }
        return $fields;
    }
}