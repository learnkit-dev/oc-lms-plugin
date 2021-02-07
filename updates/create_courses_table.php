<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_courses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();

            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_public')->default(0);

            $table->timestamp('available_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('learnkit_lms_courses');
    }
}
