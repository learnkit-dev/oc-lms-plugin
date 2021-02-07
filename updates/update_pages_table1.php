<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdatePages1Table extends Migration
{
    public function up()
    {
        Schema::table('learnkit_lms_pages', function (Blueprint $table) {
            $table->text('code_before_save')->nullable();
            $table->text('code_after_save')->nullable();
        });
    }

    public function down()
    {
        Schema::table('learnkit_lms_pages', function (Blueprint $table) {
            $table->dropColumn('code_before_save');
            $table->dropColumn('code_after_save');
        });
    }
}
