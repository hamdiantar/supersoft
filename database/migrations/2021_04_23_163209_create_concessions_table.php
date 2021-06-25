<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->string('number');
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->comment('pending, accepted, finished, rejected');
            $table->string('type')->comment('add, withdrawal');
            $table->unsignedBigInteger('concession_type_id');
            $table->unsignedBigInteger('concessionable_id');
            $table->string('concessionable_type');
            $table->integer('total_quantity')->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('concessions');
    }
}
