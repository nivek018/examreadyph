<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::create([
            'name' => 'Free Core Access',
            'slug' => 'free',
            'price' => 0,
            'duration_days' => 3650, // 10 years
            'features_json' => [
                'Full access to all 10,000+ free exam questions',
                'Taglish answer explanations included',
                '2 AI Tutor question credits',
                'Standard practice test timer',
                'Ad-supported experience',
            ],
            'question_limit' => 50,
            'ai_question_limit' => 2,
            'is_active' => true,
            'sort_order' => 1,
            'badge_color' => 'badge-blue',
        ]);

        SubscriptionPlan::create([
            'name' => 'Pro Monthly Pass',
            'slug' => 'pro-monthly',
            'price' => 149,
            'duration_days' => 30,
            'features_json' => [
                'Everything in Free Plan',
                '100% Ad-Free Experience',
                'Unlimited daily practice questions',
                '50 AI Taglish Tutor questions per month',
                'Weak-Area targeting & personalized drills',
                'Full access to Premium-only mock exams',
                'Priority support',
            ],
            'question_limit' => null, // Unlimited
            'ai_question_limit' => 50,
            'is_active' => true,
            'sort_order' => 2,
            'badge_color' => 'badge-amber',
        ]);

        SubscriptionPlan::create([
            'name' => 'VIP 1-Year Pass',
            'slug' => 'vip-annual',
            'price' => 399,
            'duration_days' => 365,
            'features_json' => [
                'Everything in Pro Monthly Pass',
                '1 Year Unlimited Access (Save over 70%)',
                '100% Ad-Free Experience',
                '500 AI Taglish Tutor questions',
                'All upcoming 2026-2027 exam syllabus updates',
                'Downloadable offline summary cheatsheets',
                'VIP Student Badge & Community Lounge Access',
            ],
            'question_limit' => null, // Unlimited
            'ai_question_limit' => 500,
            'is_active' => true,
            'sort_order' => 3,
            'badge_color' => 'badge-purple',
        ]);
    }
}
