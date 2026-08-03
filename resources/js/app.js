import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

/*
|--------------------------------------------------------------------------
| Quiz Demo Component (Landing Page Hero)
|--------------------------------------------------------------------------
*/
window.quizDemo = function () {
    return {
        currentIndex: 0,
        selected: null,
        questions: [
            {
                category: 'Civil Service — Professional',
                question: 'Which section of the 1987 Philippine Constitution guarantees free access to courts and quasi-judicial bodies?',
                options: [
                    { letter: 'A', text: 'Article III, Section 11', correct: true },
                    { letter: 'B', text: 'Article III, Section 5', correct: false },
                    { letter: 'C', text: 'Article II, Section 2', correct: false },
                    { letter: 'D', text: 'Article IV, Section 1', correct: false },
                ],
                explanation: 'Ang tamang sagot ay Article III, Section 11. Ito ang Free Access Clause na nagsasabing "Free access to the courts and quasi-judicial bodies shall not be denied to any person by reason of poverty." Ito\'y bahagi ng Bill of Rights at napakahalaga sa CSE dahil kadalasan lumabas ito bilang tricky question.'
            },
            {
                category: 'UPCAT — Science',
                question: 'What is the net charge of an atom that has 26 protons, 30 neutrons, and 24 electrons?',
                options: [
                    { letter: 'A', text: '0 (neutral)', correct: false },
                    { letter: 'B', text: '+2', correct: true },
                    { letter: 'C', text: '-2', correct: false },
                    { letter: 'D', text: '+4', correct: false },
                ],
                explanation: 'Ang atom na ito ay may 26 protons (+26) at 24 electrons (-24). Kaya ang net charge ay +26 + (-24) = +2. Isa itong iron (Fe) cation na Fe²⁺. Sa UPCAT, common ang ganitong item sa chemistry section — always remember: net charge = protons - electrons.'
            },
            {
                category: 'LET — Professional Education',
                question: 'According to Bloom\'s Taxonomy (Revised), which cognitive process is HIGHEST in the hierarchy?',
                options: [
                    { letter: 'A', text: 'Analyzing', correct: false },
                    { letter: 'B', text: 'Evaluating', correct: false },
                    { letter: 'C', text: 'Creating', correct: true },
                    { letter: 'D', text: 'Applying', correct: false },
                ],
                explanation: 'Sa revised Bloom\'s Taxonomy ni Anderson & Krathwohl (2001), ang hierarchy ay: Remember → Understand → Apply → Analyze → Evaluate → Create. Ang "Create" ang pinakamataas dahil ito ang level na nagpo-produce ng bagong output. Sa LET ProfEd, laging may 2-3 items about Bloom\'s — memorize the correct order!'
            },
        ],

        get currentQuestion() {
            return this.questions[this.currentIndex];
        },

        selectOption(idx) {
            if (this.selected !== null) return;
            this.selected = idx;
        },

        getOptionClass(idx) {
            if (this.selected === null) {
                return 'border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 hover:border-blue-500 text-slate-800 dark:text-slate-200 cursor-pointer';
            }
            if (this.currentQuestion.options[idx].correct) {
                return 'border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300';
            }
            if (idx === this.selected && !this.currentQuestion.options[idx].correct) {
                return 'border-2 border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300';
            }
            return 'border border-slate-200 dark:border-slate-700 bg-slate-100/50 dark:bg-slate-900/40 text-slate-400 dark:text-slate-500';
        },

        getIconClass(idx) {
            if (this.selected === null) return 'hidden';
            if (this.currentQuestion.options[idx].correct) {
                return 'fa-solid fa-circle-check text-emerald-500 text-lg';
            }
            if (idx === this.selected && !this.currentQuestion.options[idx].correct) {
                return 'fa-solid fa-circle-xmark text-rose-500 text-lg';
            }
            return 'hidden';
        },

        next() {
            this.currentIndex = (this.currentIndex + 1) % this.questions.length;
            this.selected = null;
        },

        reset() {
            this.selected = null;
        }
    };
};

/*
|--------------------------------------------------------------------------
| Exam Category Filter (Landing Page)
|--------------------------------------------------------------------------
*/
window.filterExams = function (category) {
    document.querySelectorAll('.exam-card').forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
};

window.Alpine = Alpine;

Alpine.start();
