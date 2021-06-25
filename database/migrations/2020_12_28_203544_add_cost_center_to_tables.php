<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostCenterToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->bigInteger('cost_center_id')->nullable();
        });
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->bigInteger('cost_center_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->dropColumn('cost_center_id');
        });
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->dropColumn('cost_center_id');
        });
    }
}
