<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('accounts_tree_root_id');
            $table->bigInteger('accounts_tree_id');
            $table->bigInteger('account_type_id');
            $table->bigInteger('account_item_id');
            $table->enum('account_nature' ,['debit' ,'credit'])->default('debit');
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
        Schema::dropIfExists('account_relations');
    }
}
