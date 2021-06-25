<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountFieldsToReceiptsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->enum('user_account_type' ,['employees' ,'customers' ,'suppliers'])->nullable();
            $table->bigInteger('user_account_id')->nullable();
        });
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->enum('user_account_type' ,['employees' ,'customers' ,'suppliers'])->nullable();
            $table->bigInteger('user_account_id')->nullable();
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
            $table->dropColumn('user_account_type');
            $table->dropColumn('user_account_id');
        });
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->dropColumn('user_account_type');
            $table->dropColumn('user_account_id');
        });
    }
}
