<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplyOrderIdToPurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('invoice_type')->default('normal')->comment('normal, from supply order');
            $table->unsignedBigInteger('supply_order_id')->nullable();
            $table->decimal('additional_payments')->default(0);
            $table->string('status')->default('pending')->comment('pending, accept, reject');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn('additional_payments');
            $table->dropColumn('supply_order_id');
            $table->dropColumn('invoice_type');
        });
    }
}
