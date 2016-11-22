<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteryMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_methods', function (Blueprint $table)
        {
            $table->increments('id');
            $table->boolean('grate')->comment('按讚');
            $table->boolean('share')->comment('分享');
            $table->boolean('reply')->comment('留言');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lottery_methods');
    }
}