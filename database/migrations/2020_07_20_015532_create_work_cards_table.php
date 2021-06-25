<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_number');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('created_by');
            $table->tinyInteger('receive_car_status')->default(0)->comment('0=>not_received, 1=>received');;
            $table->string('status')->default('pending');
            $table->date('receive_car_date');
            $table->time('receive_car_time');
            $table->tinyInteger('delivery_car_status')->default(0)->comment('0=>not_delivered, 1=>delivered');
            $table->date('delivery_car_date');
            $table->time('delivery_car_time');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('car_id')->references('id')->on('cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_cards');
    }
}
