<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $civilService = BlogCategory::create([
            'name' => 'Civil Service Tips', 'slug' => 'civil-service-tips',
            'description' => 'Study strategies and tips for passing the Civil Service Exam in the Philippines.',
            'sort_order' => 1, 'is_active' => true,
        ]);

        $let = BlogCategory::create([
            'name' => 'LET Reviewer', 'slug' => 'let-reviewer',
            'description' => 'Comprehensive guides and tips for the Licensure Examination for Teachers.',
            'sort_order' => 2, 'is_active' => true,
        ]);

        $collegeEntrance = BlogCategory::create([
            'name' => 'College Entrance', 'slug' => 'college-entrance',
            'description' => 'Preparation guides for UPCAT, DOST, and other college entrance exams.',
            'sort_order' => 3, 'is_active' => true,
        ]);

        $studyTips = BlogCategory::create([
            'name' => 'General Study Tips', 'slug' => 'general-study-tips',
            'description' => 'Universal study strategies, time management, and exam-taking techniques.',
            'sort_order' => 4, 'is_active' => true,
        ]);

        // Create tags
        $tags = [];
        foreach (['civil service', 'study tips', 'reviewer', 'LET', 'UPCAT', 'time management', 'exam strategy', 'free reviewer', 'practice test', 'Philippine exam'] as $tagName) {
            $tags[$tagName] = BlogTag::create(['name' => $tagName]);
        }

        // Create sample posts
        $adminId = \App\Models\User::where('role', 'admin')->first()?->id ?? 1;

        $post1 = BlogPost::create([
            'category_id' => $civilService->id,
            'author_id' => $adminId,
            'title' => '10 Proven Tips to Pass the Civil Service Exam on Your First Try',
            'slug' => '10-tips-pass-civil-service-exam',
            'excerpt' => 'Struggling with the Civil Service Exam? Here are 10 battle-tested strategies from passers that will dramatically improve your chances of passing on your first attempt.',
            'body' => '<h2>Why the Civil Service Exam Matters</h2>
<p>The Civil Service Exam (CSE) is the gateway to government employment in the Philippines. Whether you are aiming for a Professional or Sub-Professional level, passing this exam opens doors to thousands of career opportunities in public service.</p>

<h2>1. Understand the Exam Format</h2>
<p>The Professional Level CSE covers Vocabulary, Grammar and Correct Usage, Paragraph Organization, Reading Comprehension, Analogy, Logic, and Numerical Reasoning. Knowing what to expect eliminates surprises on exam day.</p>

<h2>2. Create a Study Schedule</h2>
<p>Dedicate at least 2-3 hours daily for 30 days before the exam. Break your study time into focused sessions covering different subjects. Use the Pomodoro technique: 25 minutes of focused study followed by a 5-minute break.</p>

<h2>3. Focus on Your Weak Areas</h2>
<p>Take a diagnostic practice test first. Identify the subjects where you scored the lowest, and dedicate more time to improving those areas. ExamReady PH has AI-powered weak-area targeting that helps you focus exactly where you need it.</p>

<h2>4. Practice with Timed Mock Exams</h2>
<p>Simulate real exam conditions by taking timed practice tests. This builds your time management skills and reduces anxiety on the actual exam day.</p>

<h2>5. Master Numerical Reasoning</h2>
<p>Many examinees struggle with math. Focus on basic operations, percentages, ratio and proportion, and number series. Practice mental math to speed up your calculations.</p>

<h2>6. Read Filipino and English Materials Daily</h2>
<p>The exam tests both English and Filipino comprehension. Read newspapers, articles, and books in both languages to improve your vocabulary and reading speed.</p>

<h2>7. Learn Elimination Strategies</h2>
<p>For multiple-choice questions, eliminate obviously wrong answers first. This increases your probability of choosing the correct answer even when you are unsure.</p>

<h2>8. Join Study Groups</h2>
<p>Studying with others helps you learn from different perspectives. Explaining concepts to others also reinforces your own understanding of the material.</p>

<h2>9. Take Care of Your Health</h2>
<p>Get adequate sleep, eat nutritious meals, and exercise regularly during your review period. A healthy body supports a sharp mind.</p>

<h2>10. Stay Positive and Consistent</h2>
<p>Believe in your ability to pass. Consistency in studying matters more than cramming. Trust the process and maintain a positive mindset throughout your preparation.</p>

<h2>Final Thoughts</h2>
<p>Passing the Civil Service Exam requires discipline, the right strategies, and quality review materials. Start your free review today on ExamReady PH and take advantage of thousands of practice questions with AI-powered explanations.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'is_featured' => true,
            'seo_title' => '10 Tips to Pass the Civil Service Exam 2026 — Free Reviewer Guide',
            'seo_description' => 'Proven strategies to pass the Philippine Civil Service Exam on your first try. Free reviewer tips, study schedule, and practice test recommendations for 2026.',
        ]);
        $post1->tags()->attach([$tags['civil service']->id, $tags['study tips']->id, $tags['exam strategy']->id]);

        $post2 = BlogPost::create([
            'category_id' => $studyTips->id,
            'author_id' => $adminId,
            'title' => 'The Pomodoro Technique: How to Study Smarter, Not Harder',
            'slug' => 'pomodoro-technique-study-smarter',
            'excerpt' => 'Learn how the Pomodoro Technique can transform your study sessions from exhausting marathons into focused, productive sprints that actually improve retention.',
            'body' => '<h2>What is the Pomodoro Technique?</h2>
<p>The Pomodoro Technique is a time management method developed by Francesco Cirillo in the late 1980s. It uses a timer to break work into intervals, traditionally 25 minutes in length, separated by short breaks.</p>

<h2>How It Works for Exam Review</h2>
<p>Here is the simple process: Choose a subject to review. Set a timer for 25 minutes. Study with full focus until the timer rings. Take a 5-minute break. After four "pomodoros," take a longer 15-30 minute break.</p>

<h2>Why It Works</h2>
<p>Our brains are not designed for hours of continuous focus. The Pomodoro Technique works because it matches our natural attention spans. Short, focused bursts followed by rest periods help maintain high-quality concentration and improve information retention.</p>

<h2>Tips for Filipino Students</h2>
<p>During your 5-minute breaks, stand up and stretch. Avoid scrolling through social media as it can extend your break time. Use breaks to hydrate and rest your eyes from the screen.</p>

<h2>Combine with ExamReady PH</h2>
<p>Use ExamReady PH practice tests during your 25-minute focus sessions. The platform automatically tracks your progress and identifies weak areas, making each pomodoro session more effective.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(7),
            'is_featured' => true,
            'seo_title' => 'Pomodoro Technique for Exam Review — Study Smarter in 2026',
            'seo_description' => 'Master the Pomodoro Technique for effective exam review. Learn how timed study sessions improve focus and retention for Philippine board exams.',
        ]);
        $post2->tags()->attach([$tags['study tips']->id, $tags['time management']->id]);

        $post3 = BlogPost::create([
            'category_id' => $let->id,
            'author_id' => $adminId,
            'title' => 'LET 2026 Comprehensive Guide: What to Study and How to Prepare',
            'slug' => 'let-2026-comprehensive-guide',
            'excerpt' => 'Everything you need to know about the 2026 Licensure Examination for Teachers. Coverage, study plan, and free reviewer recommendations.',
            'body' => '<h2>LET 2026 Overview</h2>
<p>The Licensure Examination for Teachers (LET) is administered by the Professional Regulation Commission (PRC). It consists of three components: General Education, Professional Education, and your chosen Specialization.</p>

<h2>General Education Coverage</h2>
<p>General Education covers English, Filipino, Mathematics, Science, Social Studies, and Information Technology. This component tests your foundational knowledge across multiple disciplines.</p>

<h2>Professional Education</h2>
<p>Professional Education focuses on teaching principles, methodologies, educational psychology, curriculum development, and assessment of learning. This is the heart of the LET.</p>

<h2>How to Prepare</h2>
<p>Start reviewing at least 3 months before the exam. Focus 40% of your time on Professional Education, 35% on your Specialization, and 25% on General Education. Use practice tests to gauge your readiness.</p>

<h2>Free Resources on ExamReady PH</h2>
<p>ExamReady PH offers thousands of LET practice questions with Taglish explanations. Our AI tutor can explain complex concepts in a way that is easy to understand. Start your free review today.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(1),
            'is_featured' => true,
            'seo_title' => 'LET Reviewer 2026 — Free Comprehensive Study Guide for Teachers',
            'seo_description' => 'Complete LET 2026 preparation guide with free reviewer, study plan, and practice questions. Pass the Licensure Examination for Teachers on your first attempt.',
        ]);
        $post3->tags()->attach([$tags['LET']->id, $tags['reviewer']->id, $tags['free reviewer']->id]);
    }
}
