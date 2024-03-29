<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsDepartments extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_departments', function($table)
        {
            $table->string('code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_departments', function($table)
        {
            $table->dropColumn('code');
        });
    }
}
