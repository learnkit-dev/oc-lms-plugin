<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsPages2 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_pages', function($table)
        {
            $table->smallInteger('exclude_from_export')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_pages', function($table)
        {
            $table->dropColumn('exclude_from_export');
        });
    }
}
