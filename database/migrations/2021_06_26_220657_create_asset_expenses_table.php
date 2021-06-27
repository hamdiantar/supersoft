<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number');
            $table->unsignedBigInteger('branch_id');
            $table->dateTime('dateTime')->nullable();
            $table->text('notes');
            $table->enum('status', ['accept', 'pending', 'cancel']);
            $table->double('total');
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
        Schema::dropIfExists('asset_expenses');
    }
}
