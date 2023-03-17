<?php

namespace App\Jobs;
use WpStarter\Bus\Queueable;
use WpStarter\Contracts\Queue\ShouldBeUnique;
use WpStarter\Contracts\Queue\ShouldQueue;
use WpStarter\Foundation\Bus\Dispatchable;
use WpStarter\Queue\InteractsWithQueue;
use WpStarter\Queue\SerializesModels;
use WpStarter\Support\Facades\Artisan;

class UpdatePayments implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    function handle(){
        Artisan::call('payments:update');
    }
}