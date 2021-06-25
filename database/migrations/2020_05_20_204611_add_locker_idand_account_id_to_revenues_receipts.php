<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLockerIdandAccountIdToRevenuesReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('revenue_receipts') && Schema::hasTable('lockers')
            && Schema::hasTable('accounts')) {
            Schema::table('revenue_receipts', function (Blueprint $table) {
                $table->unsignedBigInteger('locker_id')->nullable();
                $table->foreign('locker_id')->references('id')->on('lockers')->onUpdate('CASCADE');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('CASCADE');
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
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->dropForeign('locker_id');
            $table->dropForeign('account_id');
        });
    }
}
