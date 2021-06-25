<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('locker_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('branch_id');
            $table->dateTime('date');
            $table->enum('type',['deposit','withdrawal']);
            $table->decimal('amount');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('Cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('locker_id')->references('id')->on('lockers')->onDelete('Cascade');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locker_transactions');
    }
}
