<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('facebook_page_id')->unsigned()->comment('粉絲專頁 id');
            $table->string('content', 4096)->comment('文章內容');
            $table->integer('lottery_method_id')->unsigned()->default(1)->comment('抽獎方式 id');
            $table->string('awards', 1024)->nullable()->comment('獎品');
            $table->string('article_url')->comment('文章連結');
            $table->string('cover_url')->nullable()->comment('文章圖片連結|粉絲專頁封面相片');
            $table->timestamp('published_at')->comment('文章發佈時間');
            $table->timestamp('expired_at')->nullable()->comment('活動截止日期');
            $table->timestamp('announced_at')->nullable()->comment('抽獎結果公佈日期');
            $table->timestamps();
            $table->softDeletes();

            $table->index('published_at');
            $table->index('expired_at');
            $table->index('announced_at');

            $table->foreign('facebook_page_id')->references('id')->on('facebook_pages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lottery_method_id')->references('id')->on('lottery_methods')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lotteries', function (Blueprint $table)
        {
            $table->dropForeign('lotteries_facebook_page_id_foreign');
            $table->dropForeign('lotteries_lottery_method_id_foreign');
        });

        Schema::drop('lotteries');
    }
}