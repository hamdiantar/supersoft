<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardInvoiceMaintenanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_invoice_maintenance_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('card_invoice_id');
            $table->unsignedBigInteger('maintenance_type_id');
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
        Schema::dropIfExists('card_invoice_maintenance_types');
    }
}
