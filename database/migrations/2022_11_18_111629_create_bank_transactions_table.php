<?php

use WpStarter\Database\Migrations\Migration;
use WpStarter\Database\Schema\Blueprint;
use WpStarter\Support\Facades\Schema;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tid',64);
            $table->string('bank',20);
            $table->unique(['tid','bank']);
            $table->string('prefix',20)->index();
            $table->unsignedBigInteger('order_id')->default(0)->index();
            $table->decimal('amount',13);
            $table->string('currency',10)->index()->default('VND');
            $table->string('content')->index();
            $table->string('content_raw');
            $table->timestamp('received_at');
            $table->timestamp('notified_at')->nullable();
            $table->string('status',50)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_transactions');
    }
}
