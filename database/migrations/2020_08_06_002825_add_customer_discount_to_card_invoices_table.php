<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerDiscountToCardInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_invoices', function (Blueprint $table) {
            $table->decimal('customer_discount')->default(0);
            $table->string('customer_discount_type')->default('amount');
            $table->tinyInteger('customer_discount_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_invoices', function (Blueprint $table) {
            $table->dropColumn('customer_discount');
            $table->dropColumn('customer_discount_type');
            $table->dropColumn('customer_discount_status');
        });
    }
}
