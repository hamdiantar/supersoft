<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_expense_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('price');
            $table->unsignedBigInteger('asset_expense_id');
            $table->unsignedBigInteger('asset_expense_item_id');
            $table->unsignedBigInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets_tb');
            $table->foreign('asset_expense_id')->references('id')->on('asset_expenses');
            $table->foreign('asset_expense_item_id')->references('id')->on('assets_item_expenses');
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
        Schema::dropIfExists('asset_expense_items');
    }
}
