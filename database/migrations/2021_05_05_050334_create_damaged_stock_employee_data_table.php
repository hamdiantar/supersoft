<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDamagedStockEmployeeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damaged_stock_employee_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('damaged_stock_id');
            $table->unsignedBigInteger('employee_data_id');
            $table->decimal('percent')->default(0);
            $table->decimal('amount')->default(0);
            $table->timestamps();
            $table->foreign('damaged_stock_id')->references('id')->on('damaged_stocks')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('damaged_stock_employee_data');
    }
}
