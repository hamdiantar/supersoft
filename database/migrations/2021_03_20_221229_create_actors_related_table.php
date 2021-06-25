<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActorsRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actors_related', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_relation_id');
            $table->enum('actor_type' ,['customer' ,'supplier' ,'employee'])->default('customer');
            $table->enum('related_as' ,['global' ,'actor_group' ,'actor_id'])->default('global')
            ->comment('we use this column to identify the account relation for global actor (employees ,customers & suppliers) or for a group or for a custom actor');
            $table->integer('related_id')->nullable();
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
        Schema::dropIfExists('actors_related');
    }
}
