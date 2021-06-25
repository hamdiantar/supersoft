<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlternativePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alternative_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('part_id');
            $table->unsignedBigInteger('alternative_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->foreign('alternative_id')->references('id')->on('parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alternative_parts');
    }
}
