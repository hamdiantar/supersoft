<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseInvoiceReturnToRevenueReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->foreign('purchase_return_id')
                ->references('id')->on('purchase_returns');
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
            $table->dropForeign('purchase_return_id');
        });
    }
}
