<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAmountColumnInLockerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('locker_transactions', function (Blueprint $table) {
            $table->decimal('amount',10,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locker_transactions', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
}
