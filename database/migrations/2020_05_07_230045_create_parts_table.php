<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('spare_part_type_id');
            $table->unsignedBigInteger('spare_part_unit_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->text('description')->nullable();
            $table->string('img')->nullable();

            $table->decimal('selling_price');
            $table->decimal('less_selling_price');
            $table->decimal('service_selling_price');
            $table->decimal('less_service_selling_price');
            $table->integer('maximum_sale_amount')->default(0);
            $table->integer('minimum_for_order')->default(0)->comment('less quantity for new order');
            $table->decimal('biggest_percent_discount');
            $table->decimal('biggest_amount_discount');
            $table->integer('quantity')->default(0);
            $table->decimal('last_selling_price');
            $table->decimal('last_purchase_price');

            $table->boolean('status')->comment(" false=>inActive, true=>Active");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('spare_part_type_id')->references('id')->on('spare_parts');
            $table->foreign('spare_part_unit_id')->references('id')->on('spare_part_units');
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
