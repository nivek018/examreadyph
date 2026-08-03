<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subtopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $civilService = ExamCategory::create([
            'name' => 'Civil Service',
            'slug' => 'civil-service',
            'icon' => 'fa-solid fa-landmark',
            'color_class' => 'badge-amber',
            'description' => 'Civil Service Examinations for government positions.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $college = ExamCategory::create([
            'name' => 'College Entrance',
            'slug' => 'college-entrance',
            'icon' => 'fa-solid fa-graduation-cap',
            'color_class' => 'badge-blue',
            'description' => 'College entrance exam reviewers for Philippine universities.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $let = ExamCategory::create([
            'name' => 'Teachers (LET)',
            'slug' => 'teachers-let',
            'icon' => 'fa-solid fa-chalkboard-user',
            'color_class' => 'badge-purple',
            'description' => 'Licensure Examination for Teachers.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Create Exams
        $csePro = Exam::create([
            'category_id' => $civilService->id,
            'name' => 'CSE Professional Level',
            'slug' => 'cse-professional-level',
            'description' => 'Civil Service Exam - Professional Level. Covers Numerical, Analytical, Verbal, General Information, and PH Constitution.',
            'seo_title' => 'CSE Professional Level Reviewer — Free Practice ' . date('Y') . ' | ExamReady PH',
            'seo_description' => 'Free Civil Service Exam Professional Level reviewer with answer key and AI Taglish explanations. Practice 150+ questions covering Numerical Reasoning, Verbal Ability, Analytical Thinking, General Information, and PH Constitution.',
            'long_description' => "The Civil Service Exam Professional Level (CSE-PPT) is one of the most popular government exams in the Philippines. It is administered by the Civil Service Commission (CSC) and is required for those who want to work in second-level government positions.\n\nThis free online reviewer covers all five major areas tested in the Professional Level exam:\n\n• Numerical Reasoning — Word problems, percentage, ratio, number series, and basic algebra.\n• Analytical Thinking — Logic puzzles, pattern recognition, and deductive reasoning.\n• Verbal Ability — Vocabulary, grammar, reading comprehension, and sentence completion.\n• General Information — Philippine history, current events, and general knowledge.\n• Philippine Constitution — Key provisions of the 1987 Philippine Constitution.\n\nOur reviewer features AI-powered Taglish explanations that break down each answer in a mix of English and Filipino, making it easier to understand the reasoning behind each correct answer.\n\nStudy Tips:\n1. Start with Numerical Reasoning — it has the most weight in the actual exam.\n2. Use Practice Mode to focus on your weakest subtopics.\n3. Take at least 3 full Mock Exams before your exam date.\n4. Review the PH Constitution — it's often the easiest to improve on.\n\nGood luck, future government employee! 🇵🇭",
            'total_questions' => 10,
            'time_limit_seconds' => 1800,
            'passing_score_percent' => 80,
            'difficulty' => 'intermediate',
            'is_premium' => false,
            'is_active' => true,
            'shuffle_questions' => true,
            'shuffle_options' => false,
            'show_explanations' => true,
            'allow_review' => true,
        ]);

        $upcat = Exam::create([
            'category_id' => $college->id,
            'name' => 'UPCAT Reviewer',
            'slug' => 'upcat-reviewer',
            'description' => 'UP College Admission Test - covers Language Proficiency, Science, Math, and Reading Comprehension.',
            'seo_title' => 'UPCAT Reviewer — Free Practice ' . date('Y') . ' | ExamReady PH',
            'seo_description' => 'Free UPCAT reviewer with answer key. Practice Math, Science, Language Proficiency, and Reading Comprehension questions with instant AI Taglish explanations.',
            'long_description' => "The University of the Philippines College Admission Test (UPCAT) is one of the most competitive college entrance exams in the Philippines. Thousands of students apply every year, but only a fraction get accepted.\n\nThis free online reviewer covers the four main areas tested in the UPCAT:\n\n• Mathematics — Algebra, geometry, trigonometry, statistics, and calculus basics.\n• Science — Biology, chemistry, physics, and earth science.\n• Language Proficiency — English grammar, Filipino vocabulary, and sentence construction.\n• Reading Comprehension — Analyzing passages and critical thinking.\n\nStudy Tips:\n1. Math and Science carry the most weight — prioritize these subjects.\n2. Practice time management — UPCAT is known for being time-pressured.\n3. Read widely — Reading Comprehension rewards students who are well-read.\n4. Don't guess blindly — the UPCAT has a minus system for wrong answers.\n\nStart practicing now and aim for that UP dream! 💚",
            'total_questions' => 10,
            'time_limit_seconds' => 1200,
            'passing_score_percent' => 70,
            'difficulty' => 'advanced',
            'is_premium' => false,
            'is_active' => true,
            'shuffle_questions' => true,
            'show_explanations' => true,
            'allow_review' => true,
        ]);

        // Create Subtopics for CSE Professional
        $cseSubtopics = [
            ['name' => 'Numerical Reasoning', 'icon' => 'fa-solid fa-calculator', 'sort_order' => 1, 'description' => 'Practice numerical reasoning questions including word problems, percentages, ratios, number series, and basic algebra. This is the highest-weighted section in the CSE Professional exam.'],
            ['name' => 'Analytical Thinking', 'icon' => 'fa-solid fa-brain', 'sort_order' => 2, 'description' => 'Sharpen your logical reasoning skills with pattern recognition, deductive reasoning, and logic puzzle questions.'],
            ['name' => 'Verbal Ability', 'icon' => 'fa-solid fa-spell-check', 'sort_order' => 3, 'description' => 'Improve your English language skills with vocabulary, grammar, reading comprehension, and sentence completion exercises.'],
            ['name' => 'General Information', 'icon' => 'fa-solid fa-globe', 'sort_order' => 4, 'description' => 'Test your knowledge of Philippine history, geography, current events, and general knowledge topics.'],
            ['name' => 'PH Constitution', 'icon' => 'fa-solid fa-landmark', 'sort_order' => 5, 'description' => 'Review key provisions of the 1987 Philippine Constitution including the Bill of Rights, powers of government, and citizenship.'],
        ];

        $cseSubtopicMap = [];
        foreach ($cseSubtopics as $st) {
            $subtopic = Subtopic::create([
                'exam_id' => $csePro->id,
                'name' => $st['name'],
                'slug' => Str::slug($st['name']),
                'icon' => $st['icon'],
                'sort_order' => $st['sort_order'],
                'description' => $st['description'],
                'is_active' => true,
            ]);
            $cseSubtopicMap[$st['name']] = $subtopic->id;
        }

        // Create Subtopics for UPCAT
        $upcatSubtopics = [
            ['name' => 'Mathematics', 'icon' => 'fa-solid fa-square-root-variable', 'sort_order' => 1, 'description' => 'Practice algebra, geometry, trigonometry, statistics, and basic calculus problems commonly tested in the UPCAT.'],
            ['name' => 'Science', 'icon' => 'fa-solid fa-flask', 'sort_order' => 2, 'description' => 'Review biology, chemistry, physics, and earth science concepts for the UPCAT Science subtest.'],
            ['name' => 'Language Proficiency', 'icon' => 'fa-solid fa-language', 'sort_order' => 3, 'description' => 'Practice English grammar, Filipino vocabulary, and sentence construction exercises.'],
            ['name' => 'Reading Comprehension', 'icon' => 'fa-solid fa-book-reader', 'sort_order' => 4, 'description' => 'Improve your ability to analyze passages, identify main ideas, and draw inferences.'],
        ];

        $upcatSubtopicMap = [];
        foreach ($upcatSubtopics as $st) {
            $subtopic = Subtopic::create([
                'exam_id' => $upcat->id,
                'name' => $st['name'],
                'slug' => Str::slug($st['name']),
                'icon' => $st['icon'],
                'sort_order' => $st['sort_order'],
                'description' => $st['description'],
                'is_active' => true,
            ]);
            $upcatSubtopicMap[$st['name']] = $subtopic->id;
        }

        // Map section names to subtopic IDs
        $cseSection2Subtopic = [
            'PH Constitution' => $cseSubtopicMap['PH Constitution'],
            'Numerical Reasoning' => $cseSubtopicMap['Numerical Reasoning'],
            'Verbal Ability' => $cseSubtopicMap['Verbal Ability'],
            'General Information' => $cseSubtopicMap['General Information'],
            'Analytical Thinking' => $cseSubtopicMap['Analytical Thinking'],
        ];

        $upcatSection2Subtopic = [
            'Mathematics' => $upcatSubtopicMap['Mathematics'],
            'Science' => $upcatSubtopicMap['Science'],
            'Language Proficiency' => $upcatSubtopicMap['Language Proficiency'],
            'Reading Comprehension' => $upcatSubtopicMap['Reading Comprehension'],
        ];

        // Seed Questions for CSE Professional
        $cseQuestions = [
            [
                'text' => 'Which section of the 1987 Philippine Constitution guarantees free access to courts and quasi-judicial bodies?',
                'options' => ['Article III, Section 11', 'Article III, Section 5', 'Article II, Section 2', 'Article IV, Section 1'],
                'correct' => 0,
                'explanation' => 'Ang tamang sagot ay Article III, Section 11. Ito ang Free Access Clause na nagsasabing "Free access to the courts and quasi-judicial bodies shall not be denied to any person by reason of poverty."',
                'section' => 'PH Constitution',
            ],
            [
                'text' => 'What is the sum of the first 20 positive integers?',
                'options' => ['210', '200', '190', '220'],
                'correct' => 0,
                'explanation' => 'Gamitin ang formula: n(n+1)/2 = 20(21)/2 = 210. Ito ay kilala bilang Gauss formula para sa sum ng consecutive integers.',
                'section' => 'Numerical Reasoning',
            ],
            [
                'text' => 'Choose the word most similar in meaning to "UBIQUITOUS":',
                'options' => ['Omnipresent', 'Unique', 'Unusual', 'Invisible'],
                'correct' => 0,
                'explanation' => 'Ubiquitous means present everywhere. Kaya ang pinaka-malapit na kahulugan ay "omnipresent" na ibig sabihin ay nasa lahat ng dako.',
                'section' => 'Verbal Ability',
            ],
            [
                'text' => 'The Philippines gained full independence from the United States on:',
                'options' => ['July 4, 1946', 'June 12, 1898', 'February 4, 1899', 'January 23, 1899'],
                'correct' => 0,
                'explanation' => 'Ang Treaty of Manila ay nilagdaan ng July 4, 1946, na nagbigay ng ganap na kalayaan sa Pilipinas mula sa US. Ang June 12 naman ay ang proklamasyon ni Aguinaldo noong 1898.',
                'section' => 'General Information',
            ],
            [
                'text' => 'If a shirt costs ₱800 and is discounted by 25%, what is the final price?',
                'options' => ['₱600', '₱625', '₱650', '₱575'],
                'correct' => 0,
                'explanation' => '25% ng ₱800 = ₱200. Kaya ₱800 - ₱200 = ₱600. Shortcut: i-multiply ang ₱800 × 0.75 = ₱600.',
                'section' => 'Numerical Reasoning',
            ],
            [
                'text' => 'Which body has the power to declare the existence of a state of war?',
                'options' => ['Congress', 'The President', 'Supreme Court', 'Commission on Elections'],
                'correct' => 0,
                'explanation' => 'Under Article VI, Section 23 ng 1987 Constitution, ang Congress ang may kapangyarihang mag-declare ng state of war sa pamamagitan ng 2/3 vote ng both Houses.',
                'section' => 'PH Constitution',
            ],
            [
                'text' => 'What is the next number in the series: 2, 6, 12, 20, 30, __?',
                'options' => ['42', '40', '36', '44'],
                'correct' => 0,
                'explanation' => 'Ang pattern ay: +4, +6, +8, +10, +12. Kaya 30 + 12 = 42. Isa rin itong formula: n(n+1) where n = 1,2,3,4,5,6.',
                'section' => 'Analytical Thinking',
            ],
            [
                'text' => '"The teacher _____ the students before the exam." Choose the correct verb:',
                'options' => ['advised', 'adviced', 'advise', 'advises'],
                'correct' => 0,
                'explanation' => 'Ang past tense ng "advise" ay "advised." Tandaan: "advice" ay noun, "advise" ay verb. Kaya "adviced" ay mali.',
                'section' => 'Verbal Ability',
            ],
            [
                'text' => 'Who is the first female president of the Philippines?',
                'options' => ['Corazon Aquino', 'Gloria Macapagal Arroyo', 'Miriam Defensor Santiago', 'Imelda Marcos'],
                'correct' => 0,
                'explanation' => 'Si Corazon C. Aquino ang naging unang babaeng pangulo ng Pilipinas noong 1986 matapos ang EDSA People Power Revolution.',
                'section' => 'General Information',
            ],
            [
                'text' => 'A rectangular lot has a perimeter of 60 meters. If the length is twice the width, what is the area?',
                'options' => ['200 sq. m.', '150 sq. m.', '250 sq. m.', '180 sq. m.'],
                'correct' => 0,
                'explanation' => 'Let width = w, length = 2w. Perimeter: 2(w + 2w) = 60, so 6w = 60, w = 10. Length = 20. Area = 10 × 20 = 200 sq.m.',
                'section' => 'Numerical Reasoning',
            ],
        ];

        foreach ($cseQuestions as $qData) {
            $question = Question::create([
                'exam_id' => $csePro->id,
                'subtopic_id' => $cseSection2Subtopic[$qData['section']] ?? null,
                'section_name' => $qData['section'],
                'question_text' => $qData['text'],
                'explanation_taglish' => $qData['explanation'],
                'difficulty' => 'medium',
                'is_active' => true,
                'created_by' => 1,
            ]);

            $letters = ['A', 'B', 'C', 'D'];
            foreach ($qData['options'] as $i => $optText) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'letter' => $letters[$i],
                    'text' => $optText,
                    'is_correct' => $i === $qData['correct'],
                    'sort_order' => $i,
                ]);
            }
        }

        // Seed UPCAT questions
        $upcatQuestions = [
            [
                'text' => 'What is the derivative of f(x) = 3x² + 2x - 5?',
                'options' => ['6x + 2', '3x + 2', '6x² + 2', '6x - 5'],
                'correct' => 0,
                'explanation' => 'Power rule: d/dx(axⁿ) = naxⁿ⁻¹. So d/dx(3x²) = 6x, d/dx(2x) = 2, d/dx(-5) = 0. Total: 6x + 2.',
                'section' => 'Mathematics',
            ],
            [
                'text' => 'Which organelle is responsible for protein synthesis?',
                'options' => ['Ribosome', 'Mitochondria', 'Golgi apparatus', 'Lysosomes'],
                'correct' => 0,
                'explanation' => 'Ang ribosomes ang responsible sa protein synthesis. Binabasa nila ang mRNA at ginagawa ang amino acid chain.',
                'section' => 'Science',
            ],
            [
                'text' => 'Choose the sentence with correct grammar:',
                'options' => ['Neither the teacher nor the students were present.', 'Neither the teacher nor the students was present.', 'Neither the teacher or the students were present.', 'Neither the teacher or the students was present.'],
                'correct' => 0,
                'explanation' => 'Sa neither...nor construction, ang verb ay sumusunod sa subject na pinakamalapit (students = plural). Kaya "were" ang tama.',
                'section' => 'Language Proficiency',
            ],
            [
                'text' => 'If log₂(x) = 5, what is x?',
                'options' => ['32', '25', '10', '64'],
                'correct' => 0,
                'explanation' => 'log₂(x) = 5 means 2⁵ = x = 32. Tandaan: logarithm ay inverse ng exponentiation.',
                'section' => 'Mathematics',
            ],
            [
                'text' => 'The process by which plants convert light energy into chemical energy is called:',
                'options' => ['Photosynthesis', 'Cellular respiration', 'Fermentation', 'Transpiration'],
                'correct' => 0,
                'explanation' => 'Photosynthesis ang proseso ng conversion ng light energy to chemical energy (glucose) gamit ang CO₂ at H₂O sa chloroplasts.',
                'section' => 'Science',
            ],
        ];

        foreach ($upcatQuestions as $qData) {
            $question = Question::create([
                'exam_id' => $upcat->id,
                'subtopic_id' => $upcatSection2Subtopic[$qData['section']] ?? null,
                'section_name' => $qData['section'],
                'question_text' => $qData['text'],
                'explanation_taglish' => $qData['explanation'],
                'difficulty' => 'hard',
                'is_active' => true,
                'created_by' => 1,
            ]);

            $letters = ['A', 'B', 'C', 'D'];
            foreach ($qData['options'] as $i => $optText) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'letter' => $letters[$i],
                    'text' => $optText,
                    'is_correct' => $i === $qData['correct'],
                    'sort_order' => $i,
                ]);
            }
        }

        // Refresh subtopic question count caches
        Subtopic::all()->each->refreshQuestionCount();
    }
}
