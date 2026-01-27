<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->decimal('passing_marks', 8, 2)->default(0);
            $table->decimal('negative_marking_value', 8, 2)->default(0);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('randomize_options')->default(false);
            $table->boolean('one_question_at_a_time')->default(false);
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
