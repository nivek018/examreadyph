<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Subtopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Backfill seeder: creates subtopics from existing section_name values
 * and links existing questions to their subtopics.
 * Run this ONCE after the 2026_08_03_100000 migration.
 */
class SubtopicBackfillSeeder extends Seeder
{
    public function run(): void
    {
        // Subtopic definitions per exam slug
        $subtopicDefs = [
            'cse-professional-level' => [
                ['name' => 'Numerical Reasoning', 'icon' => 'fa-solid fa-calculator', 'sort_order' => 1, 'description' => 'Practice numerical reasoning questions including word problems, percentages, ratios, number series, and basic algebra.'],
                ['name' => 'Analytical Thinking', 'icon' => 'fa-solid fa-brain', 'sort_order' => 2, 'description' => 'Sharpen your logical reasoning skills with pattern recognition, deductive reasoning, and logic puzzles.'],
                ['name' => 'Verbal Ability', 'icon' => 'fa-solid fa-spell-check', 'sort_order' => 3, 'description' => 'Improve vocabulary, grammar, reading comprehension, and sentence completion skills.'],
                ['name' => 'General Information', 'icon' => 'fa-solid fa-globe', 'sort_order' => 4, 'description' => 'Test your knowledge of Philippine history, geography, current events, and general knowledge.'],
                ['name' => 'PH Constitution', 'icon' => 'fa-solid fa-landmark', 'sort_order' => 5, 'description' => 'Review key provisions of the 1987 Philippine Constitution including the Bill of Rights.'],
            ],
            'upcat-reviewer' => [
                ['name' => 'Mathematics', 'icon' => 'fa-solid fa-square-root-variable', 'sort_order' => 1, 'description' => 'Practice algebra, geometry, trigonometry, statistics, and basic calculus for the UPCAT.'],
                ['name' => 'Science', 'icon' => 'fa-solid fa-flask', 'sort_order' => 2, 'description' => 'Review biology, chemistry, physics, and earth science concepts for the UPCAT Science subtest.'],
                ['name' => 'Language Proficiency', 'icon' => 'fa-solid fa-language', 'sort_order' => 3, 'description' => 'Practice English grammar, Filipino vocabulary, and sentence construction.'],
                ['name' => 'Reading Comprehension', 'icon' => 'fa-solid fa-book-reader', 'sort_order' => 4, 'description' => 'Improve your ability to analyze passages, identify main ideas, and draw inferences.'],
            ],
        ];

        // SEO fields to update
        $seoData = [
            'cse-professional-level' => [
                'seo_title' => 'Civil Service Exam (CSE) Professional Level Reviewer ' . date('Y') . ' | ExamReady PH',
                'seo_description' => 'Free Civil Service Exam (CSE) Professional Level reviewer with answer key and AI Taglish explanations. Practice questions on Numerical Reasoning, Verbal, Analytical, General Info, and PH Constitution.',
                'long_description' => "The Civil Service Exam Professional Level (CSE-PPT) is one of the most popular government exams in the Philippines. It is administered by the Civil Service Commission (CSC) and is required for those who want to work in second-level government positions.\n\nThis free online reviewer covers all five major areas tested in the Professional Level exam:\n\n• Numerical Reasoning — Word problems, percentage, ratio, number series, and basic algebra.\n• Analytical Thinking — Logic puzzles, pattern recognition, and deductive reasoning.\n• Verbal Ability — Vocabulary, grammar, reading comprehension, and sentence completion.\n• General Information — Philippine history, current events, and general knowledge.\n• Philippine Constitution — Key provisions of the 1987 Philippine Constitution.\n\nOur reviewer features AI-powered Taglish explanations that break down each answer in a mix of English and Filipino.\n\nStudy Tips:\n1. Start with Numerical Reasoning — it has the most weight in the actual exam.\n2. Use Practice Mode to focus on your weakest subtopics.\n3. Take at least 3 full Mock Exams before your exam date.\n4. Review the PH Constitution — it's often the easiest to improve on.\n\nGood luck, future government employee! 🇵🇭",
            ],
            'upcat-reviewer' => [
                'seo_title' => 'UPCAT (UP College Admission Test) Reviewer ' . date('Y') . ' | ExamReady PH',
                'seo_description' => 'Free UPCAT (UP College Admission Test) reviewer with answer key. Practice Math, Science, Language Proficiency, and Reading Comprehension questions with instant AI Taglish explanations.',
                'long_description' => "The University of the Philippines College Admission Test (UPCAT) is one of the most competitive college entrance exams in the Philippines. Thousands of students apply every year, but only a fraction get accepted.\n\nThis free online reviewer covers the four main areas tested in the UPCAT:\n\n• Mathematics — Algebra, geometry, trigonometry, statistics, and calculus basics.\n• Science — Biology, chemistry, physics, and earth science.\n• Language Proficiency — English grammar, Filipino vocabulary, and sentence construction.\n• Reading Comprehension — Analyzing passages and critical thinking.\n\nStudy Tips:\n1. Math and Science carry the most weight — prioritize these subjects.\n2. Practice time management — UPCAT is known for being time-pressured.\n3. Read widely — Reading Comprehension rewards students who are well-read.\n4. Don't guess blindly — the UPCAT has a minus system for wrong answers.\n\nStart practicing now and aim for that UP dream! 💚",
            ],
        ];

        foreach ($subtopicDefs as $examSlug => $subtopics) {
            $exam = Exam::where('slug', $examSlug)->first();
            if (!$exam) continue;

            // Update SEO fields
            if (isset($seoData[$examSlug])) {
                $exam->update($seoData[$examSlug]);
            }

            foreach ($subtopics as $stData) {
                $subtopic = Subtopic::firstOrCreate(
                    ['exam_id' => $exam->id, 'slug' => Str::slug($stData['name'])],
                    array_merge($stData, ['exam_id' => $exam->id, 'slug' => Str::slug($stData['name'])])
                );

                // Link questions by section_name
                Question::where('exam_id', $exam->id)
                    ->where('section_name', $stData['name'])
                    ->whereNull('subtopic_id')
                    ->update(['subtopic_id' => $subtopic->id]);

                // Refresh cached count
                $subtopic->refreshQuestionCount();
            }
        }

        $this->command->info('Subtopics created and questions backfilled successfully.');
    }
}
