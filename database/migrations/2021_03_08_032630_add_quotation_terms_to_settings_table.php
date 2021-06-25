<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuotationTermsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('quotation_terms_en')->nullable();
            $table->text('quotation_terms_ar')->nullable();
            $table->tinyInteger('quotation_terms_status')->default(1)->comment('1=>active, 0=> inActive');
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
            $table->dropColumn('quotation_terms_en');
            $table->dropColumn('quotation_terms_ar');
            $table->dropColumn('quotation_terms_status');
        });
    }
}
