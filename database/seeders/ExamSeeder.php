<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

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
            'total_questions' => 10,
            'time_limit_seconds' => 1800, // 30 minutes for demo
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

        // Seed a few UPCAT questions too
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
    }
}
