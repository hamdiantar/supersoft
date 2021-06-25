<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferencesColsToDailyRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_restrictions', function (Blueprint $table) {
            $table->bigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_restrictions', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->dropColumn('reference_type');
        });
    }
}
