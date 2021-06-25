<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRestrictionTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_restriction_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('daily_restriction_id')->nullable();
            $table->bigInteger('accounts_tree_id')->nullable();
            $table->double('debit_amount' ,10 ,2)->default(0);
            $table->double('credit_amount' ,10 ,2)->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('daily_restriction_tables');
    }
}
