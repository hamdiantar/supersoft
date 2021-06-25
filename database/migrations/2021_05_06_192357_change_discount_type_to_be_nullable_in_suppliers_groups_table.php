<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDiscountTypeToBeNullableInSuppliersGroupsTable extends Migration
{
    public function up()
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            DB::statement("ALTER TABLE customer_categories MODIFY COLUMN sales_discount_type ENUM('amount','percent') NULL");
            DB::statement("ALTER TABLE customer_categories MODIFY COLUMN services_discount_type ENUM('amount','percent') NULL");
            DB::statement("ALTER TABLE customer_categories MODIFY COLUMN sales_discount decimal NULL");
            DB::statement("ALTER TABLE customer_categories MODIFY COLUMN services_discount decimal NULL");
        });
    }

    public function down()
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            //
        });
    }
}
