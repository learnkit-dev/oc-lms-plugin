<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLearnkitLmsDepartments extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_departments', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('team_id')->nullable();
            $table->string('name')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('learnkit_lms_departments');
    }
}
