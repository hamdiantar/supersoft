<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInvoiceItemReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_item_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sales_invoice_return_id');
            $table->unsignedBigInteger('sales_invoice_item_id');
            $table->unsignedBigInteger('purchase_invoice_id');
            $table->unsignedBigInteger('part_id');
            $table->integer('available_qty');
            $table->integer('return_qty');
            $table->decimal('last_selling_price');
            $table->decimal('selling_price');
            $table->enum('discount_type',['amount','percent']);
            $table->decimal('discount');
            $table->decimal('sub_total');
            $table->decimal('total_after_discount');
            $table->decimal('total');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('sales_invoice_return_id')->references('id')->on('sales_invoice_returns')->onDelete('CASCADE');
            $table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoices');
            $table->foreign('part_id')->references('id')->on('parts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_item_returns');
    }
}
