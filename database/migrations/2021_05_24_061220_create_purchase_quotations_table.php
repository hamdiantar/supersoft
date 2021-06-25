<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('purchase_request_id')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('type');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('status')->default('pending')->comment('pending, accept, reject');
            $table->date('supply_date_from');
            $table->date('supply_date_to');
            $table->decimal('sub_total')->default(0);
            $table->decimal('discount')->default(0);
            $table->string('discount_type')->default('amount')->comment('amount, percent');
            $table->decimal('total_after_discount')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('total')->default(0);
            $table->string('library_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_quotations');
    }
}
