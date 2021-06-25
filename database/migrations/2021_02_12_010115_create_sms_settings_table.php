<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('branch_id');

            $table->boolean('customer_request_status')->default(1);
            $table->text('customer_request_accept')->nullable();
            $table->text('customer_request_reject')->nullable();

            $table->boolean('quotation_request_status')->default(1);
            $table->text('quotation_request_accept')->nullable();
            $table->text('quotation_request_reject')->nullable();
            $table->text('quotation_request_pending')->nullable();

            $table->boolean('sales_invoice_status')->default(1);
            $table->text('sales_invoice_create')->nullable();
            $table->text('sales_invoice_edit')->nullable();
            $table->text('sales_invoice_delete')->nullable();

            $table->boolean('sales_invoice_return_status')->default(1);
            $table->text('sales_invoice_return_create')->nullable();
            $table->text('sales_invoice_return_edit')->nullable();
            $table->text('sales_invoice_return_delete')->nullable();

            $table->boolean('work_card_send_status')->default(1);
            $table->text('work_card_create')->nullable();
            $table->text('work_card_edit')->nullable();
            $table->text('work_card_delete')->nullable();

            $table->boolean('sales_invoice_payments_status')->default(1);
            $table->text('sales_invoice_payments_create')->nullable();
            $table->text('sales_invoice_payments_edit')->nullable();
            $table->text('sales_invoice_payments_delete')->nullable();

            $table->boolean('sales_return_payments_status')->default(1);
            $table->text('sales_return_payments_create')->nullable();
            $table->text('sales_return_payments_edit')->nullable();
            $table->text('sales_return_payments_delete')->nullable();

            $table->boolean('work_card_payments_status')->default(1);
            $table->text('work_card_payments_create')->nullable();
            $table->text('work_card_payments_edit')->nullable();
            $table->text('work_card_payments_delete')->nullable();

            $table->boolean('work_card_status')->default(1);
            $table->text('work_card_status_pending')->nullable();
            $table->text('work_card_status_processing')->nullable();
            $table->text('work_card_status_finished')->nullable();

            $table->boolean('car_follow_up_status')->default(1);
            $table->text('car_follow_up_remember')->nullable();

            $table->boolean('expenses_status')->default(1);
            $table->text('expenses_create')->nullable();
            $table->text('expenses_edit')->nullable();
            $table->text('expenses_delete')->nullable();

            $table->boolean('revenue_status')->default(1);
            $table->text('revenue_create')->nullable();
            $table->text('revenue_edit')->nullable();
            $table->text('revenue_delete')->nullable();

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
        Schema::dropIfExists('sms_settings');
    }
}
