<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExecutionTimeToTaxesFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes_fees', function (Blueprint $table) {
            $table->string('execution_time')->default('after_discount')->comment('after_discount, before_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes_fees', function (Blueprint $table) {
            $table->dropColumn('execution_time');
        });
    }
}
