<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {

            $table->bigIncrements('subscription_id');
            $table->string('client_token',256);
            $table->string('receipt_id',256);
            $table->boolean('status');
            $table->timestamp('expire_date');
            $table->timestamps();

            $table->index(['client_token','receipt_id']);
            $table->unique(['client_token','receipt_id']);
            $table->index('client_token');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
