<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_terms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('term_en');
            $table->text('term_ar');
            $table->string('type');
            $table->unsignedBigInteger('branch_id');
            $table->tinyInteger('status')->comment('1 => active, 0 => inActive');
            $table->tinyInteger('for_purchase_quotation')->comment('1 => active, 0 => inActive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_terms');
    }
}
