<?php namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSubjectResultsTable extends Migration
{
    public function up()
    {
        Schema::create('learnkit_lms_subject_results', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('user_id')->nullable();
            $table->string('content_block_hash')->nullable();
            $table->string('subject')->nullable();
            $table->integer('score')->default(0);
            $table->integer('course_id')->nullable();
            $table->integer('page_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('learnkit_lms_subject_results');
    }
}
