<?php

namespace App\Banking;

use App\Banking\Model\BankTransaction;

class TransactionsManager
{
    protected $apiManager;
    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager=$apiManager;
    }

    /**
     * Update transaction list for given bank
     * @param $bank
     * @return
     */
    function update($bank){
        $bankApi=$this->apiManager->bank($bank);
        $transactions=$bankApi->getTransactions();
        $bankApi->setHasNewTransaction(false);
        foreach ($transactions as $transaction){
            $transaction=BankTransaction::query()->updateOrCreate([
                'bank'=>$bank,
                'tid'=>$transaction->id,
            ],[
                'content'=>$transaction->content,
                'content_raw'=>$transaction->content_raw,
                'amount'=>$transaction->amount,
                'currency'=>$transaction->currency,
                'prefix'=>$transaction->prefix,
                'order_id'=>absint($transaction->order_id),
                'received_at'=>$transaction->date?:ws_now(),
            ]);
            if($transaction->wasRecentlyCreated){
                $bankApi->gotNewTransaction($transaction);
                $bankApi->setHasNewTransaction(true);
            }
        }
        $bankApi->transactionsUpdated();
        return $transactions;
    }
}