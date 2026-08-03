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

        // If no Groq API Key configured, fallback to database static explanation
        if (empty($apiKey)) {
            return $question->explanation_taglish ?? 'No explanation available.';
        }

        try {
            $questionText = $question->question_text;
            $options = $question->options->map(fn($o) => "{$o->letter}) {$o->text} " . ($o->is_correct ? '[CORRECT]' : ''))->implode("\n");
            $examName = $question->exam->name ?? 'Civil Service Examination';
            $topicName = $question->subtopic->name ?? ($question->section_name ?? 'General Knowledge');

            $systemPrompt = $this->settings->get('ai_system_prompt', "You are an expert Filipino exam tutor for ExamReadyPH.\nAnswer in Taglish (mix of Tagalog and English) to help Filipino students understand.\nBe concise, accurate, and encouraging.");
            $systemPrompt = str_replace(['{exam_name}', '{topic_name}'], [$examName, $topicName], $systemPrompt);

            $userPrompt = "Please explain this multiple choice question step-by-step in clear, encouraging Taglish:\n\n"
                        . "Question: {$questionText}\n\n"
                        . "Options:\n{$options}\n\n"
                        . ($forceRegenerate ? "Give a fresh, slightly different breakdown or analogy than before." : "Explain why the correct option is right and why other choices are wrong.");

            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
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

        // Fallback to database explanation if API call fails
        return $question->explanation_taglish ?? 'No explanation available.';
    }
}
