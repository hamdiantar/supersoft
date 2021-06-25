<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcessionExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concession_executions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('concession_id');
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->string('status')->comment('pending, late, finished');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->foreign('concession_id')->references('id')->on('concessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concession_executions');
    }
}
