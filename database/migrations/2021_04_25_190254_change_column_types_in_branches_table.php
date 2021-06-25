<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnTypesInBranchesTable extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->longText('mailbox_number')->change();
            $table->longText('postal_code')->change();
            $table->longText('fax')->change();
        });
    }

    public function down()
    {}
}
