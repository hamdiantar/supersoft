<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartPriceSegmentIdToConcessionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concession_items', function (Blueprint $table) {
            $table->unsignedBigInteger('part_price_segment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concession_items', function (Blueprint $table) {
            $table->dropColumn('part_price_segment_id');
        });
    }
}
