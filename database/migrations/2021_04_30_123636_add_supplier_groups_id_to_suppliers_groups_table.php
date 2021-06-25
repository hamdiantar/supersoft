<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierGroupsIdToSuppliersGroupsTable extends Migration
{
    public function up()
    {
        Schema::table('supplier_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_groups', 'supplier_group_id')) {
                $table->unsignedBigInteger('supplier_group_id')->nullable();
            }
            if (!Schema::hasColumn('supplier_groups', 'discount_type')) {
                $table->enum('discount_type', ['amount', 'percent'])->nullable();
            }
        });
    }

    public function down(){}
}
