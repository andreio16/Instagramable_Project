<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->string('id')->require();
            $table->string('type');
            $table->string('link');
            $table->string('filter');
            $table->string('created_time');
            $table->text('tags');
            $table->string('likes');
            $table->string('image');
            $table->integer('image_width');
            $table->integer('image_height');
            $table->timestamps();

            $table->unsignedBigInteger('user_id');    
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
