<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCreatorNameToEmployeIdInStoresTable extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
           $table->renameColumn('creator_name', 'employees_ids');
           $table->dropColumn('creator_phone');
        });
    }

    public function down(): void {}
}
