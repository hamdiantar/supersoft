<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalToStoreTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_transfer_items', function (Blueprint $table) {
            $table->unsignedBigInteger('part_price_id');
            $table->unsignedBigInteger('part_price_segment_id')->nullable();
            $table->decimal('price')->default(0);
            $table->decimal('total')->default(0);
            $table->unsignedBigInteger('new_part_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_transfer_items', function (Blueprint $table) {

            $table->dropColumn('total');
            $table->dropColumn('price');
            $table->dropColumn('part_price_segment_id');
            $table->dropColumn('part_price_id');
        });
    }
}
