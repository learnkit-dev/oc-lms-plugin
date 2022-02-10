<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLearnkitLmsDepartmentsUsers extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_departments_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('department_id');
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('learnkit_lms_departments_users');
    }
}
