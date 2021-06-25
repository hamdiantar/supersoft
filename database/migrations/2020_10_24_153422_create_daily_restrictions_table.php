<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_restrictions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restriction_number')->nullable();
            $table->bigInteger('operation_number')->nullable();
            $table->date('operation_date')->nullable();
            $table->double('debit_amount' ,10 ,2)->default(0);
            $table->double('credit_amount' ,10 ,2)->default(0);
            $table->integer('records_number')->default(0);
            $table->boolean('auto_generated')->default(0);
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
        Schema::dropIfExists('daily_restrictions');
    }
}
