<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoneyPermissionsRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_permissions_related', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_relation_id');
            $table->enum('money_gateway' ,['locker' ,'bank'])->default('locker');
            $table->enum('act_as' ,['exchange' ,'receive'])->default('exchange');
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
        Schema::dropIfExists('money_permissions_related');
    }
}
