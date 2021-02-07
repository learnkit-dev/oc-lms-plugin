<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCoursesUsersTable extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_courses_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('user_id');
            $table->integer('course_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('learnkit_lms_courses_users');
    }
}
