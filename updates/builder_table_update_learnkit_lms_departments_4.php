<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsDepartments4 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_departments', function($table)
        {
            $table->string('type')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_departments', function($table)
        {
            $table->string('type');
        });
    }
}
