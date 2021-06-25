<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('expenses_types') && !Schema::hasTable('expenses_items')) {
            Schema::create('expenses_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('item_ar')->nullable();
                $table->string('item_en')->nullable();
                $table->boolean('status')->default(1);
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('expense_id');
                $table->foreign('expense_id')->references('id')->on('expenses_types')
                    ->onDelete('CASCADE')
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
        Schema::dropIfExists('expenses_items');
    }
}
