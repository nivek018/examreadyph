<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create subtopics table
        Schema::create('subtopics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('icon')->default('fa-solid fa-book-open');
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('question_count_cache')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['exam_id', 'slug']);
            $table->index(['exam_id', 'is_active', 'sort_order']);
        });

        // 2. Add subtopic_id to questions
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('subtopic_id')->nullable()->after('section_name')
                  ->constrained('subtopics')->nullOnDelete();
        });

        // 3. Add mode + subtopic filter to exam_sessions
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->enum('mode', ['mock', 'relaxed', 'practice'])->default('mock')->after('exam_id');
            $table->json('subtopic_ids_json')->nullable()->after('question_order_json');
        });

        // 4. Add SEO fields to exams
        Schema::table('exams', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('description');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->text('long_description')->nullable()->after('seo_description');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'long_description']);
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn(['mode', 'subtopic_ids_json']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['subtopic_id']);
            $table->dropColumn('subtopic_id');
        });

        Schema::dropIfExists('subtopics');
    }
};
