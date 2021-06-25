<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsMigartion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_tb', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_ar');
            $table->unsignedBigInteger('branch_id')->default(0);
            $table->unsignedBigInteger('asset_group_id')->default(0);
            $table->unsignedBigInteger('asset_type_id')->default(0);
            $table->tinyInteger('asset_status')->default(0);
            $table->decimal('annual_consumtion_rate' , 15,2)->default('0.0');
            $table->text('asset_details')->nullable();
            $table->string('asset_age')->default('0');
            $table->string('purchase_date')->nullable();
            $table->string('date_of_work')->nullable();
            $table->decimal('purchase_cost',15,2)->default('0.0');
            $table->decimal('past_consumtion',15,2)->default('0.0');
            $table->decimal('current_consumtion',15,2)->default('0.0');
            $table->decimal('total_current_consumtion',15,2)->default('0.0');
            $table->decimal('book_value',15,2)->default('0.0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets_tb');
    }
}
