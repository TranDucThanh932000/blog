<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserAcceptedBlogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accepted_blogs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('censor_id')->unsigned()->index();
            $table->foreign('censor_id')->references('id')->on('users');
            $table->bigInteger('blog_id')->unsigned()->index();
            $table->foreign('blog_id')->references('id')->on('blogs');
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
        Schema::dropIfExists('user_accepted_blogs');
    }
}
