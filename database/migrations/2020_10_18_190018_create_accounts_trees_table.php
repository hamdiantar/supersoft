<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_trees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('accounts_tree_id')->nullable();
            $table->integer('tree_level')->default(1);
            $table->string('account_type_name_ar');
            $table->string('account_type_name_en');
            $table->enum('account_nature' ,['debit' ,'credit'])->default('debit');
            $table->string('name_ar');
            $table->string('name_en');
            $table->softDeletes();
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
        Schema::dropIfExists('accounts_trees');
    }
}
