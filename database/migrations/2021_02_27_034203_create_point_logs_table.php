<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sales_invoice_id')->nullable();
            $table->unsignedBigInteger('sales_invoice_return_id')->nullable();
            $table->unsignedBigInteger('card_invoice_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->integer('points')->default(0);
            $table->decimal('amount')->default(0);
            $table->string('log');
            $table->string('type');
            $table->decimal('setting_amount')->nullable();
            $table->integer('setting_points')->nullable();
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
        Schema::dropIfExists('point_logs');
    }
}
