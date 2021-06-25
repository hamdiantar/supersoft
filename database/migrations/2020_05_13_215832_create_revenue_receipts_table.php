<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('receiver');
            $table->string('for')->nullable();
            $table->double('cost');
            $table->enum('deportation', ['safe', 'bank']);
            $table->unsignedBigInteger('revenue_type_id');
            $table->foreign('revenue_type_id')->references('id')->on('revenue_types');
            $table->unsignedBigInteger('revenue_item_id');
            $table->foreign('revenue_item_id')->references('id')->on('revenue_items');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');
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
        Schema::dropIfExists('revenue_receipts');
    }
}
