<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateCoursesTable2 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_courses', function (Blueprint $table) {
            $table->boolean('is_score_enabled')->default(false);
        });
    }

    public function down()
    {
        Schema::table('learnkit_lms_courses', function (Blueprint $table) {
            $table->dropColumn('is_score_enabled');
        });
    }
}
