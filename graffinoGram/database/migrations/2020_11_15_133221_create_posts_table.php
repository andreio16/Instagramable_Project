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
            $table->id()->require();
            $table->text('tags');
            $table->string('type');
            $table->string('link');
            $table->text('caption');
            $table->string('likes');
            $table->string('image');
            $table->string('filter');
            $table->string('created_time');
            $table->integer('image_width');
            $table->integer('image_height');
            $table->timestamps();

            $table->unsignedBigInteger('user_id');    
            $table->index('user_id');

            $table->unsignedBigInteger('comment_id')->nullable();    
            $table->index('comment_id');
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
