<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeSalaryIdToExpensesReciptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_salary_id')->nullable();
            $table->foreign('employee_salary_id')
                ->references('id')->on('employee_salaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses_receipts', function (Blueprint $table) {
            $table->dropForeign('employee_salary_id');
        });
    }
}
