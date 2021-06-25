<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSupplierIdTypeInPartsTable extends Migration
{
    public function up(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            if (Schema::hasColumn('parts', 'supplier_id')) {
                $table->dropColumn('supplier_id');
            }
            $table->text('suppliers_ids')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('suppliers_ids');
        });
    }
}
