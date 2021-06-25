<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdAssetsGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('assets_groups')) {
            Schema::table('assets_groups', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('assets_groups')) {
            Schema::table('assets_groups', function (Blueprint $table) {
                $table->dropColumn('branch_id');
            });
        }
    }
}
