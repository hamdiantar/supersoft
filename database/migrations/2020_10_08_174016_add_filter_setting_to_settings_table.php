<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilterSettingToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->tinyInteger('invoice_setting')->default(1)->comment(' 0 => simple , 1 => advanced ');
            $table->tinyInteger('filter_setting')->default(1)->comment(' 0 => simple , 1 => advanced ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('invoice_setting');
            $table->dropColumn('filter_setting');
        });
    }
}
