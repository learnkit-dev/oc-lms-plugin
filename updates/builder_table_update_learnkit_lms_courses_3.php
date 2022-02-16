<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsCourses3 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_courses', function($table)
        {
            $table->string('subtitle')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_courses', function($table)
        {
            $table->dropColumn('subtitle');
        });
    }
}
