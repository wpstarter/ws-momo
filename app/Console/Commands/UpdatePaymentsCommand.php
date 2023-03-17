<?php

namespace App\Console\Commands;

use App\Banking\TransactionsManager;
use App\Jobs\ProcessPayments;
use WpStarter\Console\Command;
use WpStarter\Support\Facades\Cache;

class UpdatePaymentsCommand extends Command
{
    protected $signature='payments:update';
    function handle(TransactionsManager $transactionsManager){
        if(Cache::lock('payments:update',10)->get()) {
            $transactions=$transactionsManager->update('momo');
            ProcessPayments::dispatch();
            foreach ($transactions as $transaction){
                if(!$transaction->order_id){
                    $this->warn('Cannot find order id on: '.$transaction->content);
                }else{
                    $this->line('Got payment for '.$transaction->order_id);
                }
            }
            $this->info('Payments updated successfully');
        }else{
            $this->error('Calling too fast');
        }
    }
}