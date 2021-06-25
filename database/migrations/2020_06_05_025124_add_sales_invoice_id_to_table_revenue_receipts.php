<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesInvoiceIdToTableRevenueReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('revenue_receipts')) {
            Schema::table('revenue_receipts', function (Blueprint $table) {
                $table->unsignedBigInteger('sales_invoice_id')->nullable();
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
        if (Schema::hasTable('revenue_receipts')) {
            Schema::table('revenue_receipts', function (Blueprint $table) {
                $table->double('sales_invoice_id');
            });
        }
    }
}
