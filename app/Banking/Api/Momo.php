<?php

namespace App\Banking\Api;

use App\Banking\Api\Momo\MomoApi;
use Carbon\Carbon;
use WpStarter\Support\Str;

class Momo extends BankApi
{

    function transactionsUpdated()
    {
        if($this->hasNewTransaction){
            MomoApi::make()->reloadUserData(true);
        }
    }

    public function getTransactions()
    {
        $momo=new MomoApi();
        $list=$momo->getNotifications();
        if(!is_array($list)){
            $list=[];
        }
        $collect=ws_collect($list);
        $receivedKeywords=['Nhận tiền','Receive'];
        $transactions=$collect->filter(function($tran)use($receivedKeywords){
            $caption=ws_data_get($tran,'caption');
            return Str::contains($caption,$receivedKeywords);
        });
        $transactions=$transactions->map(function($momo){
            $extra=json_decode($momo['extra'],true);
            $transaction=new Transaction();
            $transaction->bank='momo';
            $transaction->id=$momo['tranId'];
            $transaction->amount=$extra['amount'];
            $transaction->date=Carbon::createFromTimestamp($momo['updateTime']/1000);
            $transaction->content_raw=$momo['caption'].'('.($extra['partnerId']??'').'). '.$momo['body'];
            $transaction->content=$extra['comment'];
            $transaction->prefix='';
            $transaction->order_id='';
            $prefixes=$this->getPrefixes();
            foreach ($prefixes as $prefixKey => $prefix) {
                $content = Str::lower($transaction->content) . ' ';
                $regex = Str::lower($prefix);
                $tokens = [];
                if (Str::contains($regex, '{orderno}')) {
                    $tokens[] = Str::replace('{orderno}', '(\d+) ', $regex);
                    $tokens[] = Str::replace('{orderno}', '(\d+)\-', $regex);
                } else {
                    $tokens[] = $regex . '(\d+) ';
                    $tokens[] = $regex . '(\d+)\-';
                }
                foreach ($tokens as $token) {
                    $regex = '#' . $token . '#';
                    if (preg_match($regex, $content, $matches)) {
                        $transaction->order_id = $matches[1];
                        $transaction->prefix=$prefixKey;
                        break;
                    }
                }
            }
            return $transaction;
        });
        return $transactions;
    }
}