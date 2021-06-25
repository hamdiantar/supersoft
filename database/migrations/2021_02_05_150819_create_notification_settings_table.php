<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->tinyInteger('customer_request')->default(1);
            $table->tinyInteger('quotation_request')->default(1);
            $table->tinyInteger('work_card_status_to_user')->default(1);
            $table->tinyInteger('minimum_parts_request')->default(1);
            $table->tinyInteger('end_work_card_employee')->default(1);
            $table->tinyInteger('end_residence_employee')->default(1);
            $table->tinyInteger('end_medical_insurance_employee')->default(1);
            $table->tinyInteger('quotation_request_status')->default(1);
            $table->tinyInteger('sales_invoice')->default(1);
            $table->tinyInteger('return_sales_invoice')->default(1);
            $table->tinyInteger('work_card')->default(1);
            $table->tinyInteger('work_card_status_to_customer')->default(1);
            $table->tinyInteger('sales_invoice_payments')->default(1);
            $table->tinyInteger('return_sales_invoice_payments')->default(1);
            $table->tinyInteger('work_card_payments')->default(1);
            $table->tinyInteger('follow_up_cars')->default(1);
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
        Schema::dropIfExists('notification_settings');
    }
}
