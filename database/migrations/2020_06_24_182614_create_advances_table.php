<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('deportation', ['safe', 'bank']);
            $table->enum('operation', ['deposit', 'withdrawal']);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->double('amount' ,10 ,2)->default(0);
            $table->double('rest' ,10 ,2)->default(0);
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
        Schema::dropIfExists('advances');
    }
}
