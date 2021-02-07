<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('course_id')->nullable();

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();

            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_public')->default(0);

            $table->integer('sort_order')->nullable();

            $table->text('content_blocks')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('learnkit_lms_pages');
    }
}
