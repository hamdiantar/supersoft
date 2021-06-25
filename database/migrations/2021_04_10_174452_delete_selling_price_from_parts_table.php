<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSellingPriceFromPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parts', function (Blueprint $table) {

            $table->dropForeign('parts_store_id_foreign');
            $table->dropForeign('parts_spare_part_type_id_foreign');

            $table->dropColumn(['barcode', 'store_id', 'selling_price', 'less_selling_price', 'service_selling_price', 'less_service_selling_price',
                'maximum_sale_amount', 'minimum_for_order', 'biggest_percent_discount', 'biggest_amount_discount',
                'last_selling_price', 'last_purchase_price', 'purchase_price', 'spare_part_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parts', function (Blueprint $table) {
            //
        });
    }
}
