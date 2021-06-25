<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInvoiceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('sales_invoice_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->date('date');
            $table->time('time');
            $table->enum('type',['cash','credit']);
            $table->integer('number_of_items');
            $table->enum('discount_type',['amount','percent']);
            $table->decimal('discount');
            $table->decimal('tax');
            $table->decimal('sub_total');
            $table->decimal('total_after_discount');
            $table->decimal('total');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('sales_invoice_id')->references('id')->on('sales_invoices')->onDelete('CASCADE');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_returns');
    }
}
