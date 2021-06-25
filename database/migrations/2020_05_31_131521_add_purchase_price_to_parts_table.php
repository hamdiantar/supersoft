<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchasePriceToPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('parts')) {
            Schema::table('parts', function (Blueprint $table) {
                $table->decimal('purchase_price')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('parts')) {
            Schema::table('parts', function (Blueprint $table) {
                $table->dropColumn('purchase_price');
            });
        }
    }
}
