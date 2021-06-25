<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number');
            $table->unsignedBigInteger('work_card_id');
            $table->unsignedBigInteger('created_by');
            $table->date('date');
            $table->time('time');
            $table->enum('type',['cash','credit']);
            $table->text('terms')->nullable();
            $table->enum('discount_type',['amount','percent'])->default('amount');
            $table->decimal('discount')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('sub_total')->default(0);
            $table->decimal('total_after_discount')->default(0);
            $table->decimal('total')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('work_card_id')->references('id')->on('work_cards');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_invoices');
    }
}
