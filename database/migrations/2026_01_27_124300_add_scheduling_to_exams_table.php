<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('course_id')->after('created_by')->constrained()->onDelete('cascade');
            $table->dateTime('active_from')->nullable()->after('course_id');
            $table->dateTime('active_until')->nullable()->after('active_from');
        });
    }

    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
            $table->dropColumn(['active_from', 'active_until']);
        });
    }
};
