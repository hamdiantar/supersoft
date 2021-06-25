<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFKShiftInEmployeeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_settings', function (Blueprint $table) {
            $table->dropForeign('employee_settings_shift_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_settings', function (Blueprint $table) {
            $table->foreign('shift_id')->references('id')->on('shifts');
        });
    }
}
