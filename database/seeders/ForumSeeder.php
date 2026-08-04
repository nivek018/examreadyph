<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Discussion',
                'slug' => 'general-discussion',
                'description' => 'Chat about anything related to exams, studying, and career goals in the Philippines.',
                'icon' => 'fa-solid fa-comments',
                'sort_order' => 1,
            ],
            [
                'name' => 'Civil Service Exam',
                'slug' => 'civil-service-exam',
                'description' => 'Tips, strategies, and practice questions for the Professional and Sub-Professional CSE.',
                'icon' => 'fa-solid fa-building-columns',
                'sort_order' => 2,
            ],
            [
                'name' => 'LET Board Exam',
                'slug' => 'let-board-exam',
                'description' => 'Discuss General Education, Professional Education, and Major subjects for the Licensure Exam for Teachers.',
                'icon' => 'fa-solid fa-chalkboard-user',
                'sort_order' => 3,
            ],
            [
                'name' => 'College Entrance Exams',
                'slug' => 'college-entrance-exams',
                'description' => 'UPCAT, DOST, ACET, and other college admission test preparation discussions.',
                'icon' => 'fa-solid fa-school',
                'sort_order' => 4,
            ],
            [
                'name' => 'Study Tips & Strategies',
                'slug' => 'study-tips-strategies',
                'description' => 'Share and discover effective study techniques, time management tips, and review strategies.',
                'icon' => 'fa-solid fa-lightbulb',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            ForumCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
