<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartPriceSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_price_segments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('part_price_id');
            $table->string('name');
            $table->decimal('purchase_price')->default(0);
            $table->decimal('sales_price')->default(0);
            $table->decimal('maintenance_price')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_price_segments');
    }
}
