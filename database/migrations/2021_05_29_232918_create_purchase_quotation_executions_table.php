<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseQuotationExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_quotation_executions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_quotation_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('status')->comment('pending, late, finished');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('purchase_quotation_id')->references('id')->on('purchase_quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_quotation_executions');
    }
}
