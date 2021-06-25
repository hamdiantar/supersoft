<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_related', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_relation_id');
            $table->enum('related_as' ,['store' ,'branch-stores'])->default('store');
            $table->integer('related_id')->comment('this column for store id or branch id depend on related_as value');
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
        Schema::dropIfExists('stores_related');
    }
}
