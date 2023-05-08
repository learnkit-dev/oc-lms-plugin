<?php

namespace LearnKit\LMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateUsersTable2 extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale')->nullable()->default('nl');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locale');
        });
    }
}
