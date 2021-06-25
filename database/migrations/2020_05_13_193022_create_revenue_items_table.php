<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('revenue_types') && !Schema::hasTable('revenue_items')) {
            Schema::create('revenue_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('item_ar')->nullable();
                $table->string('item_en')->nullable();
                $table->boolean('status')->default(1);
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('revenue_id')->nullable();
                $table->foreign('revenue_id')->references('id')->on('revenue_types')
                    ->onUpdate('CASCADE');
                $table->unsignedBigInteger('branch_id')->nullable();
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
        Schema::dropIfExists('revenue_items');
    }
}
