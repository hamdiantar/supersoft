<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPointsDiscountToSalesInvoiceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_invoice_returns', function (Blueprint $table) {
            $table->decimal('points_discount')->default(0);
            $table->unsignedBigInteger('points_rule_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_invoice_returns', function (Blueprint $table) {
            $table->dropColumn('points_discount');
            $table->dropColumn('points_rule_id');
        });
    }
}
