<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceTypeToLockerReceivePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locker_receive_permissions', function (Blueprint $table) {
            $table->enum('source_type' ,['locker' ,'bank'])->default('locker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locker_receive_permissions', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });
    }
}
