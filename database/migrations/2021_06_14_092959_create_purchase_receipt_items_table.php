<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReceiptItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receipt_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_receipt_id');
            $table->unsignedBigInteger('supply_order_item_id');
            $table->unsignedBigInteger('part_id');
            $table->unsignedBigInteger('part_price_id');
            $table->integer('total_quantity')->default(0);
            $table->integer('refused_quantity')->default(0);
            $table->integer('accepted_quantity')->default(0);
            $table->decimal('defect_percent')->default(0);
            $table->timestamps();
            $table->foreign('purchase_receipt_id')->references('ID')->on('purchase_receiptS')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_receipt_items');
    }
}
