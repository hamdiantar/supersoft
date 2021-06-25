<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvanceIdToRevenueReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('advance_id')->nullable();
            $table->foreign('advance_id')
                ->references('id')->on('advances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('revenue__receipts', function (Blueprint $table) {
            $table->dropForeign('advance_id');
        });
    }
}
