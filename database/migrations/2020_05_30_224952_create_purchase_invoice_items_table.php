<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_invoice_id');
            $table->unsignedBigInteger('part_id');
            $table->unsignedBigInteger('store_id');
            $table->integer('available_qty');
            $table->integer('purchase_qty');
            $table->decimal('last_purchase_price');
            $table->decimal('purchase_price');
            $table->enum('discount_type',['amount','percent']);
            $table->decimal('discount')->default(0);
            $table->decimal('total_after_discount')->default(0);
            $table->timestamps();
            $table->foreign('purchase_invoice_id')->references('id')
                ->on('purchase_invoices')->onDelete('CASCADE');
            $table->foreign('part_id')->references('id')->on('parts');
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
        Schema::dropIfExists('purchase_invoice_items');
    }
}
