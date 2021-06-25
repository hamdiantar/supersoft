<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAccountNatureInAccountRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_relations', function (Blueprint $table) {
            DB::statement(
                "ALTER TABLE account_relations
                MODIFY account_nature ENUM(
                    'debit', 'credit', 'locker' ,'bank_acc' ,
                    'locker_exchange' ,'bank_exchange' ,'locker_receive' ,'bank_receive'
                )
                NOT NULL DEFAULT 'debit'"
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_relations', function (Blueprint $table) {
            DB::statement(
                "ALTER TABLE account_relations
                MODIFY account_nature ENUM('debit', 'credit', 'locker' ,'bank_acc')
                NOT NULL DEFAULT 'debit'"
            );
        });
    }
}
