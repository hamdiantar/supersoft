<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierDiscountToPurchaseQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_quotations', function (Blueprint $table) {
            $table->decimal('supplier_discount')->default(0);
            $table->string('supplier_discount_type')->default('amount');
            $table->tinyInteger('supplier_discount_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_quotations', function (Blueprint $table) {
            $table->dropColumn('supplier_discount_active');
            $table->dropColumn('supplier_discount_type');
            $table->dropColumn('supplier_discount');
        });
    }
}
