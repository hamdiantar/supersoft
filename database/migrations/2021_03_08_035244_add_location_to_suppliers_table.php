<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('tax_number')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->decimal('maximum_fund_on')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('tax_number');
            $table->double('lat');
            $table->double('long');
            $table->decimal('maximum_fund_on');
        });
    }
}
