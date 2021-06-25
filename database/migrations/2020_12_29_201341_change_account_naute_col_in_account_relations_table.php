<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAccountNauteColInAccountRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE account_relations MODIFY account_nature ENUM('debit', 'credit', 'locker' ,'bank_acc') NOT NULL DEFAULT 'debit'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE account_relations MODIFY account_nature ENUM('debit', 'credit') NOT NULL DEFAULT 'debit'");
    }
}
