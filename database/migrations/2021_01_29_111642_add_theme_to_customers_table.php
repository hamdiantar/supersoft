<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThemeToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $colors = [
                'red' ,'violet' ,'dark-blue' ,'blue' ,'light-blue' ,'green' ,'yellow' ,'orange' ,'chocolate' ,'dark-green'
            ];
            $table->enum('theme' ,$colors)->default('dark-blue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('theme');
        });
    }
}
