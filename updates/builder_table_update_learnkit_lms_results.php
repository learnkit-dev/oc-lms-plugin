<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsResults extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_results', function($table)
        {
            $table->integer('h5p_result_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_results', function($table)
        {
            $table->dropColumn('h5p_result_id');
        });
    }
}
