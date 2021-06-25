<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceTypeToBankReceivePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_receive_permissions', function (Blueprint $table) {
            $table->enum('source_type' ,['locker' ,'bank'])->default('bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_receive_permissions', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });
    }
}
