<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePermissionRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_permission_related', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_relation_id');
            $table->enum('permission_nature' ,['exchange' ,'receive'])->default('exchange');
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
        Schema::dropIfExists('store_permission_related');
    }
}
