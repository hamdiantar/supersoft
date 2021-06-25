<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeRewardDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_reward_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['discount', 'reward']);
            $table->date('date');
            $table->text('reason')->nullable();
            $table->double('cost');
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
        Schema::dropIfExists('employee_reward_discounts');
    }
}
