<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalToCardInvoiceMaintenanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_invoice_maintenance_types', function (Blueprint $table) {
            $table->string('discount_type')->default('amount');
            $table->decimal('discount')->default(0);
            $table->decimal('sub_total')->default(0);
            $table->decimal('total_after_discount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_invoice_maintenance_types', function (Blueprint $table) {
            $table->dropColumn('discount_type');
            $table->dropColumn('discount');
            $table->dropColumn('sub_total');
            $table->dropColumn('total_after_discount');
        });
    }
}
