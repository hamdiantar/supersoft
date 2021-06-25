<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdToAccountingModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts_trees', function (Blueprint $table) {
            $table->bigInteger('branch_id')->nullable();
        });
        Schema::table('account_relations', function (Blueprint $table) {
            $table->bigInteger('branch_id')->nullable();
        });
        Schema::table('daily_restrictions', function (Blueprint $table) {
            $table->bigInteger('branch_id')->nullable();
        });
        Schema::table('cost_centers', function (Blueprint $table) {
            $table->bigInteger('branch_id')->nullable();
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->bigInteger('branch_id')->nullable();
        });
        Schema::table('adverse_restriction_logs', function (Blueprint $table) {
            $table->bigInteger('branch_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts_trees', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('account_relations', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('daily_restrictions', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('cost_centers', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('adverse_restriction_logs', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
    }
}
