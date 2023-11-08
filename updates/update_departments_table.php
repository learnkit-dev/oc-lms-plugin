<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_departments', function (Blueprint $table) {
            $table->string('college')->nullable();
            $table->string('college_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('learnkit_lms_departments', function (Blueprint $table) {
            $table->dropColumn('college');
            $table->dropColumn('college_code');
        });
    }
}
