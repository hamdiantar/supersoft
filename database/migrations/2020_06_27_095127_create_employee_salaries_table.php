<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_id');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->double('salary' ,10 ,2)->default(0);
            $table->double('insurances' ,10 ,2)->default(0);
            $table->double('allowances' ,10 ,2)->default(0);
            $table->boolean('advance_included')->default(1);
            $table->date('date')->nullable();
            $table->enum('deportation_method' ,['bank' ,'locker']);
            $table->bigInteger('locker_id')->nullable();
            $table->bigInteger('account_id')->nullable();
            $table->longText('employee_data')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->softDeletes();
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
        Schema::dropIfExists('employee_salaries');
    }
}
