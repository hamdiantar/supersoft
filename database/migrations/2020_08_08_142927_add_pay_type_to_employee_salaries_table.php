<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayTypeToEmployeeSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->enum('pay_type' ,['cash' ,'credit'])->default('cash');
            $table->double('paid_amount' ,10 ,2)->default(0);
            $table->double('rest_amount' ,10 ,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn('pay_type');
            $table->dropColumn('paid_amount');
            $table->dropColumn('rest_amount');
        });
    }
}
