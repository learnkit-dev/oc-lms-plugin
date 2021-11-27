<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLearnkitLmsCoursesTeams extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_courses_teams', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('course_id');
            $table->integer('team_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('learnkit_lms_courses_teams');
    }
}
