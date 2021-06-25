<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierBarcodeToPartPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_prices', function (Blueprint $table) {
            $table->string('supplier_barcode')->nullable();
            $table->decimal('damage_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_prices', function (Blueprint $table) {
            $table->dropColumn('damage_price');
            $table->dropColumn('supplier_barcode');
        });
    }
}
