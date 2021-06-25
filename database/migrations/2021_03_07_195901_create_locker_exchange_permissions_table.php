<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerExchangePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_exchange_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('branch_id');
            $table->bigInteger('from_locker_id');
            $table->bigInteger('to_locker_id');
            $table->bigInteger('cost_center_id');
            $table->bigInteger('locker_receive_permission_id')->nullable();
            $table->bigInteger('employee_id');
            $table->string('permission_number');
            $table->date('operation_date');
            $table->double('amount' ,10 ,2);
            $table->enum('status' ,['pending' ,'approved' ,'rejected'])->default('pending');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('locker_exchange_permissions');
    }
}
