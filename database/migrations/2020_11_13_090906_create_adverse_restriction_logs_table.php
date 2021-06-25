<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdverseRestrictionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverse_restriction_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('accounts_tree_id')->nullable();
            $table->bigInteger('fiscal_year')->nullable();
            $table->date('date')->nullable();
            $table->date('date_to')->nullable();
            $table->date('date_from')->nullable();
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
        Schema::dropIfExists('adverse_restriction_logs');
    }
}
