<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountTreeCodeToDailyRestrictionTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_restriction_tables', function (Blueprint $table) {
            $table->string('account_tree_code')->nullable();
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
            $table->dropColumn('account_tree_code');
        });
    }
}
