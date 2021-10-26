<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('devices', function (Blueprint $table) {

            $table->bigIncrements('device_id');
            $table->string('uid',32);
            $table->string('appId',32);
            $table->string('language',2);
            $table->string('operating_system',64);
            $table->string('client_token', 256);
            $table->timestamps();

            $table->index(['uid', 'appId']);
            $table->unique(['uid', 'appId']);
            $table->unique(['client_token']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
