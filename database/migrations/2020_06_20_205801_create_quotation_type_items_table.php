<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationTypeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_type_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quotation_type_id');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('purchase_invoice_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('price')->nullable();
            $table->enum('discount_type',['amount','percent']);
            $table->decimal('discount');
            $table->decimal('sub_total');
            $table->decimal('total_after_discount');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('quotation_type_id')->references('id')->on('quotation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_type_items');
    }
}
