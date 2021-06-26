<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssestItemExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_item_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_ar');
            $table->string('item_en');
            $table->unsignedBigInteger('assets_type_expenses_id');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('assets_type_expenses_id')->references('id')->on('assets_type_expenses');
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
        Schema::dropIfExists('assets_item_expenses');
    }
}
