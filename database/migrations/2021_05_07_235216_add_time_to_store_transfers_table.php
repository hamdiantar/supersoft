<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeToStoreTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->time('time');
            $table->decimal('total')->default(0);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_transfers', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('total');
            $table->dropColumn('time');
            $table->dropColumn('user_id');
        });
    }
}
