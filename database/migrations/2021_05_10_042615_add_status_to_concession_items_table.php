<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToConcessionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concession_items', function (Blueprint $table) {
            $table->string('accepted_status')->default('pending');
            $table->string('log_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concession_items', function (Blueprint $table) {
            $table->dropColumn('log_message');
            $table->dropColumn('accepted_status');
        });
    }
}
