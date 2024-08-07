<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-blog.database.table', 'laravel_blog'), function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('page_group');
            $table->string('slug');
            $table->string('category_id')->nullable();
            $table->tinyText('title');
            $table->longText('content');
            $table->boolean('status')->default(true);
            $table->boolean('generate_by_ai')->default(false);
            $table->boolean('publish_on_medium')->default(false);
            $table->string('medium_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('primary_image')->nullable();
            $table->string('tags')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->unique(['page_group', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
