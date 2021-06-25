<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultPurchaseToPartPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_prices', function (Blueprint $table) {
            $table->boolean('default_purchase')->default(0);
            $table->boolean('default_sales')->default(0);
            $table->boolean('default_maintenance')->default(0);
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
            $table->dropColumn('default_maintenance');
            $table->dropColumn('default_sales');
            $table->dropColumn('default_purchase');
        });
    }
}
