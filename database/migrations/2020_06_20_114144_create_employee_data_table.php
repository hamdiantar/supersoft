<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('Functional_class')->nullable();
            $table->string('address')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('id_number')->nullable();
            $table->date('end_date_id_number')->nullable();
            $table->date('start_date_assign')->nullable();
            $table->date('start_date_stay')->nullable();
            $table->date('end_date_stay')->nullable();
            $table->date('end_date_health')->nullable();
            $table->string('number_card_work')->nullable();

            $table->boolean('status')->default(1)->nullable();
            $table->string('cv')->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->tinyInteger('rating')->nullable()->default(0);

            $table->unsignedBigInteger('employee_setting_id');
            $table->foreign('employee_setting_id')->references('id')->on('employee_settings');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');

            $table->unsignedBigInteger('national_id')->nullable();
            $table->foreign('national_id')->references('id')->on('countries');
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
        Schema::dropIfExists('employee_data');
    }
}
