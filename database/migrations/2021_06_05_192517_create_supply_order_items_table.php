<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supply_order_id');
            $table->unsignedBigInteger('part_id');
            $table->unsignedBigInteger('part_price_id');
            $table->unsignedBigInteger('part_price_segment_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price')->default(0);
            $table->decimal('sub_total')->default(0);
            $table->decimal('discount')->default(0);
            $table->string('discount_type')->default('amount')->comment('amount, percent');
            $table->decimal('total_after_discount')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('total')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('supply_order_items');
    }
}
