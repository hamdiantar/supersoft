<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidRemainingToPurchaseInvoiceTable extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->double('paid' ,10 ,2)->default(0);
            $table->double('remaining' ,10 ,2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn('paid');
            $table->dropColumn('remaining');
        });
    }
}
