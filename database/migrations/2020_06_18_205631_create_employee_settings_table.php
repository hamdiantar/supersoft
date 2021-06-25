<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSettingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('employee_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->time('time_attend');
            $table->time('time_leave');
            $table->integer('daily_working_hours')->default(0);
            $table->integer('annual_vocation_days')->default(0);
            $table->bigInteger('max_advance');
            $table->bigInteger('amount_account')->default(0);
            $table->boolean('status')->default(true);
            $table->enum('type_account', ['work_card', 'days', 'month']);
            $table->enum('type_absence', ['discount_day', 'fixed_salary']);
            $table->integer('type_absence_equal');
            $table->enum('hourly_extra', ['hourly_extra', 'fixed_salary']);
            $table->integer('hourly_extra_equal');
            $table->enum('hourly_delay', ['hourly_delay', 'fixed_salary']);
            $table->integer('hourly_delay_equal');
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);
            $table->boolean('monday')->default(false);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(false);
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_settings');
    }
}
