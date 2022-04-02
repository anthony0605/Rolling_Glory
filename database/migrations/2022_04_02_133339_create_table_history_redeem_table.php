<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHistoryRedeemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_redeem', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string("id_redeem");
            $table->string("poins");
            $table->string("sisa_poins");
            $table->timestamps();
            $table->string("id_seq")->autoIncrement();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_redeem');
    }
}
