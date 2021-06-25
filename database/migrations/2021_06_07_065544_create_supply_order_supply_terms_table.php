<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyOrderSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_order_supply_terms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supply_order_id');
            $table->unsignedBigInteger('supply_term_id');
            $table->foreign('supply_order_id')->references('id')->on('supply_orders')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_order_supply_terms');
    }
}
