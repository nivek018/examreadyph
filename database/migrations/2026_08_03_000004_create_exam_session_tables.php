<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('time_limit_seconds')->default(3600);
            $table->unsignedInteger('current_question_index')->default(0);
            $table->enum('status', ['in_progress', 'completed', 'abandoned', 'expired'])->default('in_progress');
            $table->decimal('score', 5, 2)->nullable();
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->unsignedInteger('unanswered_count')->default(0);
            $table->json('question_order_json')->nullable(); // Stores shuffled question IDs
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'exam_id', 'status']);
            $table->index(['status', 'expires_at']);
        });

        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exam_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->boolean('is_correct')->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->boolean('is_saved')->default(false);
            $table->unsignedInteger('time_spent_seconds')->default(0);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'question_id']);
            $table->index(['session_id', 'is_flagged']);
            $table->index(['session_id', 'is_saved']);
        });

        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->unsignedInteger('total_attempts')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->decimal('best_score', 5, 2)->nullable();
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_sessions');
    }
};
