<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesRevenuesRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_revenues_related', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_relation_id');
            $table->enum('related_as' ,['debit' ,'credit'])->default('debit');
            $table->integer('type_id');
            $table->integer('item_id');
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
        Schema::dropIfExists('expenses_revenues_related');
    }
}
