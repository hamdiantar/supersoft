<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardInvoiceWinchRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_invoice_winch_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('card_invoice_type_id');
            $table->double('branch_lat');
            $table->double('branch_long');
            $table->double('request_lat');
            $table->double('request_long');
            $table->float('distance');
            $table->decimal('price');
            $table->decimal('sub_total');
            $table->enum('discount_type',['amount','percent']);
            $table->decimal('discount');
            $table->decimal('total');
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
        Schema::dropIfExists('card_invoice_winch_requests');
    }
}
