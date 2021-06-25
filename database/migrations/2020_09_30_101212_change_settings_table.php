<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('value','sales_invoice_terms_ar');
            $table->dropColumn('name');
            $table->longText('sales_invoice_terms_en')->nullable();
            $table->renameColumn('status','sales_invoice_status');
            $table->longText('maintenance_terms_ar')->nullable();
            $table->longText('maintenance_terms_en')->nullable();
            $table->tinyInteger('maintenance_status');
            $table->unsignedBigInteger('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('sales_invoice_terms_en');
            $table->dropColumn('maintenance_terms_ar');
            $table->dropColumn('maintenance_terms_en');
            $table->dropColumn('maintenance_status');
        });
    }
}
