<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsCourses2 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_courses', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_courses', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
