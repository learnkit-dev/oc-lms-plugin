<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateDepartmentsTable1 extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_departments', function (Blueprint $table) {
            $table->string('kostenplaats')->nullable()->change();
        });
    }
}
