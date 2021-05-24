<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsCourses extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_courses', function($table)
        {
            $table->integer('team_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_courses', function($table)
        {
            $table->dropColumn('team_id');
        });
    }
}
