<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ===== General =====
            ['key' => 'site_name', 'value' => 'ExamReady PH', 'type' => 'string', 'group' => 'general', 'label' => 'Site Name', 'description' => 'The name displayed across the site.'],
            ['key' => 'site_description', 'value' => 'Free Philippine Exam Reviewer with AI Taglish Explanations — UPCAT, Civil Service, LET, NMAT', 'type' => 'text', 'group' => 'general', 'label' => 'Site Description', 'description' => 'Default meta description for SEO.'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'bool', 'group' => 'general', 'label' => 'Maintenance Mode', 'description' => 'Enable maintenance mode to block all public access.'],
            ['key' => 'registration_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'general', 'label' => 'Registration Enabled', 'description' => 'Allow new user registrations.'],
            ['key' => 'announcement_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'general', 'label' => 'Announcement Bar Enabled', 'description' => 'Show the top announcement bar on the website.'],

            // ===== Exam Config =====
            ['key' => 'default_time_limit_seconds', 'value' => '3600', 'type' => 'int', 'group' => 'exam', 'label' => 'Default Time Limit (seconds)', 'description' => 'Default exam time limit. 3600 = 1 hour. 0 = no limit.'],
            ['key' => 'default_questions_per_exam', 'value' => '50', 'type' => 'int', 'group' => 'exam', 'label' => 'Default Questions Per Exam', 'description' => 'Number of questions served per exam session by default.'],
            ['key' => 'auto_submit_on_expire', 'value' => 'true', 'type' => 'bool', 'group' => 'exam', 'label' => 'Auto Submit on Expire', 'description' => 'Automatically submit and score the exam when time runs out.'],
            ['key' => 'show_correct_answers_after', 'value' => 'true', 'type' => 'bool', 'group' => 'exam', 'label' => 'Show Correct Answers After', 'description' => 'Show correct answers and explanations after exam submission.'],
            ['key' => 'allow_exam_resume', 'value' => 'true', 'type' => 'bool', 'group' => 'exam', 'label' => 'Allow Exam Resume', 'description' => 'Allow users to resume in-progress exams.'],
            ['key' => 'session_expiry_grace_minutes', 'value' => '5', 'type' => 'int', 'group' => 'exam', 'label' => 'Session Expiry Grace (minutes)', 'description' => 'Extra minutes after time limit before session is marked expired.'],

            // ===== Freemium =====
            ['key' => 'free_question_limit_per_day', 'value' => '50', 'type' => 'int', 'group' => 'freemium', 'label' => 'Free Question Limit Per Day', 'description' => 'Max questions a free user can answer per day. Change this anytime.'],
            ['key' => 'free_exam_access', 'value' => 'true', 'type' => 'bool', 'group' => 'freemium', 'label' => 'Free Exam Access', 'description' => 'Allow free users to access non-premium exams.'],
            ['key' => 'premium_only_exams_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'freemium', 'label' => 'Premium-Only Exams', 'description' => 'Enable premium-only exam restrictions.'],

            // ===== Ads =====
            ['key' => 'ads_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'ads', 'label' => 'Ads Enabled', 'description' => 'Master on/off toggle for the entire ad popup system.'],
            ['key' => 'ads_show_to_premium_users', 'value' => 'false', 'type' => 'bool', 'group' => 'ads', 'label' => 'Show Ads to Premium Users', 'description' => 'If enabled, premium/paid users also see ad popups.'],
            ['key' => 'ads_show_after_questions', 'value' => '5', 'type' => 'int', 'group' => 'ads', 'label' => 'Show Ad After N Questions', 'description' => 'Show an ad popup after every N questions answered.'],
            ['key' => 'ads_initial_delay_seconds', 'value' => '60', 'type' => 'int', 'group' => 'ads', 'label' => 'Initial Delay (seconds)', 'description' => 'Wait this many seconds after page load before first ad.'],
            ['key' => 'ads_interval_seconds', 'value' => '180', 'type' => 'int', 'group' => 'ads', 'label' => 'Interval Between Ads (seconds)', 'description' => 'Minimum seconds between ad popups.'],
            ['key' => 'ads_max_per_session', 'value' => '3', 'type' => 'int', 'group' => 'ads', 'label' => 'Max Ads Per Session', 'description' => 'Maximum number of ad popups per exam session or page session.'],
            ['key' => 'ads_auto_dismiss_seconds', 'value' => '10', 'type' => 'int', 'group' => 'ads', 'label' => 'Auto Dismiss (seconds)', 'description' => 'Auto-close ad popup after N seconds. 0 = manual close only.'],
            ['key' => 'ads_show_on_browse', 'value' => 'true', 'type' => 'bool', 'group' => 'ads', 'label' => 'Show on Browse Pages', 'description' => 'Show ads while browsing questions and reviewer pages.'],
            ['key' => 'ads_show_on_forum', 'value' => 'true', 'type' => 'bool', 'group' => 'ads', 'label' => 'Show on Forum', 'description' => 'Show ads on forum pages.'],
            ['key' => 'ads_upgrade_cta_text', 'value' => 'Upgrade to Premium for an ad-free experience!', 'type' => 'string', 'group' => 'ads', 'label' => 'Upgrade CTA Text', 'description' => 'Message shown on ad popup to encourage upgrading.'],

            // ===== Payment =====
            ['key' => 'active_payment_gateway', 'value' => 'prepayph', 'type' => 'string', 'group' => 'payment', 'label' => 'Active Payment Gateway', 'description' => 'Currently active payment gateway: prepayph, paymongo, xendit.'],
            ['key' => 'prepayph_api_key', 'value' => '', 'type' => 'string', 'group' => 'payment', 'label' => 'PrepayPH API Key', 'description' => 'PrepayPH API public key.'],
            ['key' => 'prepayph_secret_key', 'value' => '', 'type' => 'string', 'group' => 'payment', 'label' => 'PrepayPH Secret Key', 'description' => 'PrepayPH API secret key (encrypted).'],
            ['key' => 'paymongo_api_key', 'value' => '', 'type' => 'string', 'group' => 'payment', 'label' => 'PayMongo API Key', 'description' => 'PayMongo API public key.'],
            ['key' => 'paymongo_secret_key', 'value' => '', 'type' => 'string', 'group' => 'payment', 'label' => 'PayMongo Secret Key', 'description' => 'PayMongo API secret key (encrypted).'],
            ['key' => 'gcash_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'payment', 'label' => 'GCash Enabled', 'description' => 'Allow GCash as payment method.'],
            ['key' => 'maya_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'payment', 'label' => 'Maya Enabled', 'description' => 'Allow Maya as payment method.'],
            ['key' => 'bank_transfer_enabled', 'value' => 'false', 'type' => 'bool', 'group' => 'payment', 'label' => 'Bank Transfer Enabled', 'description' => 'Allow bank transfer as payment method.'],

            // ===== AI Config =====
            ['key' => 'ai_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'ai', 'label' => 'AI Tutor Enabled', 'description' => 'Master toggle for AI Q&A feature.'],
            ['key' => 'groq_api_key', 'value' => '', 'type' => 'string', 'group' => 'ai', 'label' => 'Groq API Key', 'description' => 'API Key from Groq Cloud (gsk_...). Overrides .env fallback.'],
            ['key' => 'groq_model', 'value' => 'llama-3.3-70b-versatile', 'type' => 'string', 'group' => 'ai', 'label' => 'Groq AI Model', 'description' => 'Model used for Groq API (e.g., llama-3.3-70b-versatile, llama-3.1-8b-instant).'],
            ['key' => 'ai_free_user_quota', 'value' => '2', 'type' => 'int', 'group' => 'ai', 'label' => 'Free User AI Quota', 'description' => 'Lifetime AI question quota for free users.'],
            ['key' => 'ai_premium_user_quota', 'value' => '50', 'type' => 'int', 'group' => 'ai', 'label' => 'Premium User AI Quota', 'description' => 'Monthly AI question quota for premium users (refreshes on renewal).'],
            ['key' => 'ai_system_prompt', 'value' => "You are an expert Filipino exam tutor for ExamReadyPH.\nAnswer in Taglish (mix of Tagalog and English) to help Filipino students understand.\nThe student is preparing for: {exam_name}.\nCurrent topic: {topic_name}.\nBe concise, accurate, and encouraging.\nReference Philippine laws, PRC guidelines, or CSC regulations when relevant.\nIf the question is about a specific exam item, explain WHY the correct answer is right and WHY the other choices are wrong.", 'type' => 'text', 'group' => 'ai', 'label' => 'AI System Prompt', 'description' => 'The instruction prompt sent to AI. Use {exam_name} and {topic_name} as placeholders.'],
            ['key' => 'ai_primary_provider', 'value' => 'groq', 'type' => 'string', 'group' => 'ai', 'label' => 'Primary AI Provider', 'description' => 'Primary AI provider (groq, openrouter, openai).'],
            ['key' => 'ai_fallback_provider', 'value' => 'openrouter', 'type' => 'string', 'group' => 'ai', 'label' => 'Fallback AI Provider', 'description' => 'Fallback AI provider when primary is unavailable.'],
            ['key' => 'ai_paid_provider', 'value' => 'openai', 'type' => 'string', 'group' => 'ai', 'label' => 'Paid AI Provider', 'description' => 'Paid fallback provider when free providers are exhausted.'],
            ['key' => 'ai_primary_model', 'value' => 'llama-3.3-70b-versatile', 'type' => 'string', 'group' => 'ai', 'label' => 'Primary AI Model', 'description' => 'Model used by primary provider.'],
            ['key' => 'ai_paid_model', 'value' => 'gpt-4o-mini', 'type' => 'string', 'group' => 'ai', 'label' => 'Paid AI Model', 'description' => 'Model used by paid fallback provider.'],
            ['key' => 'ai_monthly_budget_cap_php', 'value' => '500', 'type' => 'int', 'group' => 'ai', 'label' => 'Monthly Budget Cap (₱)', 'description' => 'Maximum monthly spend on paid AI APIs in PHP.'],
            ['key' => 'ai_temperature', 'value' => '0.7', 'type' => 'string', 'group' => 'ai', 'label' => 'AI Temperature', 'description' => 'AI response creativity (0.0 = deterministic, 1.0 = creative).'],

            // ===== SEO =====
            ['key' => 'auto_post_enabled', 'value' => 'false', 'type' => 'bool', 'group' => 'seo', 'label' => 'Auto Post Enabled', 'description' => 'Enable automatic SEO article generation.'],
            ['key' => 'auto_post_frequency', 'value' => 'daily', 'type' => 'string', 'group' => 'seo', 'label' => 'Auto Post Frequency', 'description' => 'How often auto-post runs: daily, weekly.'],
            ['key' => 'default_og_image', 'value' => '/images/og-default.jpg', 'type' => 'string', 'group' => 'seo', 'label' => 'Default OG Image', 'description' => 'Default Open Graph image for social sharing.'],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'string', 'group' => 'seo', 'label' => 'Google Analytics ID', 'description' => 'Google Analytics tracking ID (e.g., G-XXXXXXXXXX).'],

            // ===== Forum =====
            ['key' => 'forum_enabled', 'value' => 'true', 'type' => 'bool', 'group' => 'forum', 'label' => 'Forum Enabled', 'description' => 'Enable the community forum.'],
            ['key' => 'forum_min_posts_to_link', 'value' => '5', 'type' => 'int', 'group' => 'forum', 'label' => 'Min Posts to Post Links', 'description' => 'Minimum posts before a user can include external links (anti-spam).'],
            ['key' => 'forum_auto_flag_keywords', 'value' => '["spam","buy now","click here","free money","earn money"]', 'type' => 'json', 'group' => 'forum', 'label' => 'Auto-Flag Keywords', 'description' => 'Posts containing these keywords are auto-flagged for moderation.'],
            ['key' => 'forum_require_email_verification', 'value' => 'true', 'type' => 'bool', 'group' => 'forum', 'label' => 'Require Email Verification', 'description' => 'Require email verification before posting in the forum.'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
