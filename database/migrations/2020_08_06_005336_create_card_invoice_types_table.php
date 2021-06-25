<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardInvoiceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_invoice_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('card_invoice_id');
            $table->unsignedBigInteger('maintenance_detection_id');
            $table->string('type');
            $table->timestamps();
            $table->foreign('card_invoice_id')->references('id')->on('card_invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_invoice_types');
    }
}
