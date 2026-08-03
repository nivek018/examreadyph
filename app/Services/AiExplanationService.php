<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiExplanationService
{
    public function __construct(protected SettingsService $settings) {}

    /**
     * Generate or fetch AI Taglish explanation for a given question.
     */
    public function explainQuestion(Question $question, bool $forceRegenerate = false): string
    {
        $apiKey = $this->settings->get('groq_api_key');
        $model = $this->settings->get('groq_model') ?: 'llama-3.1-8b-instant';

        // If no Groq API Key configured, construct a rich fallback explanation
        if (empty($apiKey)) {
            return $this->formatFallbackExplanation($question);
        }

        try {
            $questionText = $question->question_text;
            $options = $question->options->map(fn($o) => "{$o->letter}) {$o->text} " . ($o->is_correct ? '[CORRECT ANSWER]' : ''))->implode("\n");
            $examName = $question->exam->name ?? 'Civil Service Examination';
            $topicName = $question->subtopic->name ?? ($question->section_name ?? 'General Knowledge');

            $systemPrompt = "You are an expert Filipino exam tutor for ExamReady PH.\n"
                          . "Your goal is to explain Civil Service exam questions in clear, friendly, and structured Taglish (Tagalog-English mix).\n"
                          . "Format your response clearly into these 3 sections:\n"
                          . "1. 💡 **Bakit ito ang Tamang Sagot?** (Explain the concept, law, or formula step-by-step).\n"
                          . "2. ❌ **Bakit Mali ang Ibang Options?** (Briefly explain why other choices are incorrect).\n"
                          . "3. 📌 **Exam Tip for CSC Takers:** (A practical memory tip or shortcut for the test).\n"
                          . "Avoid simply repeating the question text or answer text. Provide real educational context and value.";

            $userPrompt = "Please explain this {$examName} ({$topicName}) question in structured Taglish:\n\n"
                        . "Question: {$questionText}\n\n"
                        . "Choices:\n{$options}\n\n"
                        . ($forceRegenerate ? "Provide a fresh, slightly different breakdown or analogy than before." : "Explain step-by-step why the correct choice is right and why the other options are wrong.");

            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 600,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if (!empty($content)) {
                    return trim($content);
                }
            } else {
                Log::warning('Groq API response error', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Throwable $e) {
            Log::error('AiExplanationService error: ' . $e->getMessage());
        }

        return $this->formatFallbackExplanation($question);
    }

    /**
     * Format a rich, structured fallback explanation when Groq API is not connected.
     */
    protected function formatFallbackExplanation(Question $question): string
    {
        $correctOpt = $question->options->firstWhere('is_correct', true);
        $correctLetter = $correctOpt->letter ?? 'A';
        $correctText = $correctOpt->text ?? '';
        $wrongOpts = $question->options->where('is_correct', false)->pluck('text')->map(fn($t) => "• {$t}")->implode("\n");

        $dbExp = trim($question->explanation_taglish ?? '');

        $output = "💡 **Bakit Option {$correctLetter} ({$correctText}) ang Tamang Sagot?**\n";
        if (!empty($dbExp)) {
            $output .= "{$dbExp}\n\n";
        } else {
            $output .= "Sa ilalim ng Civil Service Examination standards, ang Option {$correctLetter} ang tanging kumpletong tumutugon sa hinihingi ng tanong.\n\n";
        }

        if ($wrongOpts) {
            $output .= "❌ **Bakit Mali ang Ibang Options?**\n{$wrongOpts}\nAng mga pagpipiliang ito ay hindi ganap na umaangkop o kulang sa itinatakda ng batas/prinsipyo.\n\n";
        }

        $output .= "📌 **Exam Tip for CSC Takers:** Tandaan na sa Civil Service Exam, laging piliin ang pinaka-direct at legally/logically accurate na probisyon o formula!";

        return $output;
    }
}
