<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostCenterColumnsToDailyRestrictionTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_restriction_tables', function (Blueprint $table) {
            $table->bigInteger('cost_center_root_id')->nullable();
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
        Schema::table('daily_restriction_tables', function (Blueprint $table) {
            $table->dropColumn('cost_center_root_id');
            $table->dropColumn('cost_center_id');
        });
    }
}
