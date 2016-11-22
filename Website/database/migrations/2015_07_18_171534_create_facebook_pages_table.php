<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_pages', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->comment('粉絲專頁名稱');
            $table->string('page_id')->comment('粉絲專頁 id');
            $table->string('avatar_url')->nullable()->comment('大頭貼');
            $table->string('cover_url')->nullable()->comment('封面相片');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('page_id');

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('facebook_pages');
    }
}