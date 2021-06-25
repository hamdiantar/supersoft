<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestinationTypeToBankExchangePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_exchange_permissions', function (Blueprint $table) {
            $table->enum('destination_type' ,['locker' ,'bank'])->default('bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_exchange_permissions', function (Blueprint $table) {
            $table->dropColumn('destination_type');
        });
    }
}
