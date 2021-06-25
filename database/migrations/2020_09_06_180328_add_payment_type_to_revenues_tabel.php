<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTypeToRevenuesTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenue_receipts', function (Blueprint $table) {
            $table->enum('payment_type', ['check', 'cash', 'network']);
            $table->string('check_number')->nullable();
            $table->string('bank_name')->nullable();
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
            $table->dropColumn('payment_type');
            $table->dropColumn('check_number');
            $table->dropColumn('bank_name');
        });
    }
}
