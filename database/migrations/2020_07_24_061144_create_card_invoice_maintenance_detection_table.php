<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardInvoiceMaintenanceDetectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_invoice_maintenance_detection', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('card_invoice_id');
            $table->unsignedBigInteger('maintenance_detection_id');
            $table->unsignedBigInteger('maintenance_type_id');
            $table->json('images')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('degree')->comment(' 1 => low , 2 => average, 3 => high');
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
        Schema::dropIfExists('card_invoice_maintenance_detection');
    }
}
