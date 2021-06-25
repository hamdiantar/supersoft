<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardInvoiceTypeItemsEmployeeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_invoice_type_items_employee_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('percent',10,2)->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee_data')
                ->onDelete('CASCADE');

            $table->foreign('item_id')->references('id')
                ->on('card_invoice_type_items')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_invoice_type_items_employee_data');
    }
}
