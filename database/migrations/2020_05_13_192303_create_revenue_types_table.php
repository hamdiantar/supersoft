<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('revenue_types')) {
            Schema::create('revenue_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type_en');
                $table->string('type_ar');
                $table->boolean('status')->default(1);
                $table->unsignedBigInteger('branch_id');
                $table->foreign('branch_id')->references('id')->on('branches');
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
        Schema::dropIfExists('revenue_types');
    }
}
