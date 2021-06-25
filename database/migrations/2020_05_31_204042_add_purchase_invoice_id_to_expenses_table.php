<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseInvoiceIdToExpensesTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('expenses_receipts')) {
            Schema::table('expenses_receipts', function (Blueprint $table) {
                $table->unsignedBigInteger('purchase_invoice_id')->nullable();
                $table->foreign('purchase_invoice_id')
                    ->references('id')->on('purchase_invoices');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('expenses_receipts')) {
            Schema::table('expenses_receipts', function (Blueprint $table) {
                $table->dropForeign('purchase_invoice_id');
            });
        }
    }
}
