<?php

namespace App\Console\Commands;

use App\Banking\Model\BankTransaction;
use App\Banking\Support\Invoice;
use App\Banking\Support\Payment;
use App\Banking\TransactionsManager;
use App\Models\User;
use Carbon\Carbon;
use WpStarter\Console\Command;

class ProcessPaymentsCommand extends Command
{
    protected $signature='payments:process {--recheck}';
    function handle(TransactionsManager $transactionsManager){
        $query=BankTransaction::query();
        $query->where(function($query){
            $query->whereNull('notified_at');
            if($this->option('recheck')){
                $query->orWhere('status','not_enough_amount');
            }
        });
        $query->where('order_id','!=','0');
        $this->line('Found: '.$query->count().' transactions');
        $query->each(function(BankTransaction $transaction){
            if($transaction->order_id){
                if($transaction->prefix==='invoice'){
                    $this->processOrder($transaction);
                }
            }
        });
    }
    function processOrder(BankTransaction $transaction){
        $order=wc_get_order($transaction->order_id);
        $transaction->notified_at=Carbon::now();
        if(!$order){
            $transaction->update(['status'=>'order_not_found']);
        }else{
            $invoice=Invoice::forOrder($order);
            if(round($invoice->getAmount(),2)<=round($transaction->amount,2)){
                $transaction->update(['status'=>'ok']);
                $order->update_status('processing','Transaction #'.$transaction->id.' amount '.$transaction->amount.' on '.$transaction->received_at);
            }else{
                $transaction->update(['status'=>'not_enough_amount']);
                $order->add_order_note('Chưa đủ tiền. Số tiền nhận được: '.$transaction->amount. 'Cần thanh toán: '.$invoice->getAmount());
            }
        }
    }
}