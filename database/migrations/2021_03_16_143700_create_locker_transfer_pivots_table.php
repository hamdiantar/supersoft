<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerTransferPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_transfer_pivots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('locker_transfer_id');
            $table->bigInteger('locker_receive_permission_id');
            $table->bigInteger('locker_exchange_permission_id');
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
        Schema::dropIfExists('locker_transfer_pivots');
    }
}
