<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForAccountTreeIdToDailyRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_restrictions', function (Blueprint $table) {
            $table->bigInteger('for_account_tree_id')->nullable();
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
            $table->dropColumn('for_account_tree_id');
        });
    }
}
