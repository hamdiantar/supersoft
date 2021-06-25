<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAttendancesTable extends Migration
{
    public function up(): void
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['attendance', 'departure']);
            $table->date('date');
            $table->time('time');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('employee_data_id');
            $table->foreign('employee_data_id')->references('id')->on('employee_data');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
}
