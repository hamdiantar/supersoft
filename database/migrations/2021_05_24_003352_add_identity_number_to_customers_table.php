<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentityNumberToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('identity_number')->nullable();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('identity_number')->nullable();
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
            $table->dropColumn('identity_number');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('identity_number');
        });
    }
}
