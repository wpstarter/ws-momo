<?php

namespace App\Jobs;
use App\Banking\Model\BankTransaction;
use App\Banking\Support\Invoice;
use Carbon\Carbon;
use WpStarter\Bus\Queueable;
use WpStarter\Contracts\Queue\ShouldQueue;
use WpStarter\Foundation\Bus\Dispatchable;
use WpStarter\Queue\InteractsWithQueue;
use WpStarter\Queue\SerializesModels;
use WpStarter\Support\Facades\Artisan;

class ProcessPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    function handle(){
        Artisan::call('payments:process');
    }
}