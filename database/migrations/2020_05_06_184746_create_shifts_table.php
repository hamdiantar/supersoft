<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('branches')) {
            Schema::create('shifts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name_ar');
                $table->string('name_en');
                $table->time('start_from');
                $table->time('end_from');
                $table->integer('Saturday')->default(0);
                $table->integer('sunday')->default(0);
                $table->integer('monday')->default(0);
                $table->integer('tuesday')->default(0);
                $table->integer('wednesday')->default(0);
                $table->integer('thursday')->default(0);
                $table->integer('friday')->default(0);
                $table->unsignedBigInteger('branch_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
