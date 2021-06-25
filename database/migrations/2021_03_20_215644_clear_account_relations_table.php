<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClearAccountRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_relations' ,function (Blueprint $table) {
            $table->dropColumn('account_type_id');
            $table->dropColumn('account_item_id');
            $table->dropColumn('account_nature');
            $table->dropColumn('locker_bank_id');
            $table->string('related_model_name')->comment('we use this column to identify the data to complete the account relations where to found');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_relations' ,function (Blueprint $table) {
            $table->integer('account_type_id');
            $table->integer('account_item_id');
            $table->enum('account_nature' ,[
                'debit', 'credit', 'locker' ,'bank_acc' , 'locker_exchange' ,'bank_exchange' ,'locker_receive' ,'bank_receive'
            ])->default('debit');
            $table->integer('locker_bank_id')->nullable();
            $table->dropColumn('related_model_name');
        });
    }
}
