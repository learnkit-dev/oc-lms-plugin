<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsDepartments2 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_departments', function($table)
        {
            $table->text('extra_data')->jsonable()->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_departments', function($table)
        {
            $table->dropColumn('extra_data');
        });
    }
}
