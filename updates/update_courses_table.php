<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_courses', function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }

    public function down()
    {
        Schema::table('learnkit_lms_courses', function (Blueprint $table) {
            $table->dropColumn('properties');
        });
    }
}
