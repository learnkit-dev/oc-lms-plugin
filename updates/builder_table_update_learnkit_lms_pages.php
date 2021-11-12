<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLearnkitLmsPages extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_pages', function($table)
        {
            $table->string('code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('learnkit_lms_pages', function($table)
        {
            $table->dropColumn('code');
        });
    }
}
