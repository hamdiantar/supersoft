<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesInvoiceReturnIdToExpensesReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_invoice_return_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->dropColumn('sales_invoice_return_id');
        });
    }
}
