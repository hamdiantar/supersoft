<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeDelaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_delays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['delay', 'extra']);
            $table->date('date');
            $table->integer('number_of_hours');
            $table->integer('number_of_minutes');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('employee_data_id');
            $table->foreign('employee_data_id')->references('id')->on('employee_data');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_delays');
    }
}
