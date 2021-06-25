<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('part_id');
            $table->unsignedBigInteger('unit_id');
            $table->string('barcode')->nullable();
            $table->decimal('selling_price')->default(0);
            $table->decimal('purchase_price')->default(0);
            $table->decimal('less_selling_price')->default(0);
            $table->decimal('service_selling_price')->default(0);
            $table->decimal('less_service_selling_price')->default(0);
            $table->integer('maximum_sale_amount')->default(0);
            $table->integer('minimum_for_order')->default(0)->comment('less quantity for new order');
            $table->decimal('biggest_percent_discount')->default(0);
            $table->decimal('biggest_amount_discount')->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('last_selling_price')->default(0);
            $table->decimal('last_purchase_price')->default(0);
            $table->timestamps();

            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_prices');
    }
}
