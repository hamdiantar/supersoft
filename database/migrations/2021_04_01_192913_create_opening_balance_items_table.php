<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpeningBalanceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_balance_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('opening_balance_id');
            $table->integer('part_id');
            $table->integer('part_price_id');
            $table->integer('part_price_price_segment_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('default_unit_quantity')->default(0);
            $table->double('buy_price' ,10 ,2)->default(0);
            $table->integer('store_id');
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
        Schema::dropIfExists('opening_balance_items');
    }
}
