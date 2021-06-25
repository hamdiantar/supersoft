<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationWinchRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_winch_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quotation_type_id');
            $table->double('branch_lat');
            $table->double('branch_long');
            $table->double('request_lat');
            $table->double('request_long');
            $table->float('distance');
            $table->decimal('price');
            $table->decimal('sub_total');
            $table->enum('discount_type',['amount','percent']);
            $table->decimal('discount');
            $table->decimal('total');
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
        Schema::dropIfExists('quotation_winch_requests');
    }
}
