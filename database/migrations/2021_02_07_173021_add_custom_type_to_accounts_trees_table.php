<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomTypeToAccountsTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts_trees', function (Blueprint $table) {
            $table->integer('custom_type')->default(1)->comment('1 : Budget ,2 : Income List, 3 : Trading Account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts_trees', function (Blueprint $table) {
            $table->dropColumn('custom_type');
        });
    }
}
