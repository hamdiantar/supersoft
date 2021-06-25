<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_ar');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('address')->nullable();
            $table->enum('type',['person','company']);
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('commercial_number')->nullable();
            $table->string('tax_card')->nullable();
            $table->boolean('status');
            $table->decimal('funds_for')->default(0);
            $table->decimal('funds_on')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('branch_id')->references('id')->on('branches');
//            $table->foreign('group_id')->references('id')->on('supplier_groups');
//            $table->foreign('country_id')->references('id')->on('countries');
//            $table->foreign('city_id')->references('id')->on('cities');
//            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
