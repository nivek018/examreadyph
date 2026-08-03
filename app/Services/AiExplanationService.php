<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiExplanationService
{
    public function __construct(protected SettingsService $settings) {}

    /**
     * Generate or fetch AI explanation for a given question.
     */
    public function explainQuestion(Question $question, bool $forceRegenerate = false): string
    {
        $apiKey = $this->settings->get('groq_api_key');
        $model = $this->settings->get('groq_model') ?: 'llama-3.1-8b-instant';

        if (empty($apiKey)) {
            return $this->formatFallbackExplanation($question);
        }

        try {
            $questionText = $question->question_text;
            $options = $question->options->map(fn($o) => "{$o->letter}) {$o->text} " . ($o->is_correct ? '[CORRECT]' : ''))->implode("\n");
            $examName = $question->exam->name ?? 'Civil Service Examination';

            $systemPrompt = "You are a helpful Filipino exam tutor for ExamReady PH.\n"
                          . "Explain the question briefly and clearly in simple Taglish.\n"
                          . "STRICT FORMATTING RULES:\n"
                          . "1. DO NOT use emojis.\n"
                          . "2. DO NOT use markdown headings (no # or ##).\n"
                          . "3. DO NOT use bold symbols (no **).\n"
                          . "4. Keep the explanation short, clean, and concise (under 120 words).\n"
                          . "5. State clearly why the correct answer is right and why the other options are wrong.";

            $userPrompt = "Explain this {$examName} question concisely in clean plain text:\n\n"
                        . "Question: {$questionText}\n\n"
                        . "Choices:\n{$options}";

            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.6,
                    'max_tokens' => 300,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if (!empty($content)) {
                    return $this->cleanText($content);
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
     * Clean plain text without emojis or markdown symbols.
     */
    protected function cleanText(string $text): string
    {
        // Strip markdown headers (#, ##, ###)
        $text = preg_replace('/^#+\s*/m', '', $text);
        // Strip asterisks and backticks
        $text = str_replace(['**', '*', '`'], '', $text);
        // Strip common emojis
        $text = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1F1E0}-\x{1F1FF}]/u', '', $text);
        return trim($text);
    }

    /**
     * Format a clean, concise fallback explanation when Groq API is not connected.
     */
    protected function formatFallbackExplanation(Question $question): string
    {
        $correctOpt = $question->options->firstWhere('is_correct', true);
        $correctLetter = $correctOpt->letter ?? 'A';
        $correctText = $correctOpt->text ?? '';
        $dbExp = trim($question->explanation_taglish ?? '');

        $output = "Ang tamang sagot ay Option {$correctLetter} ({$correctText}).\n\n";

        if (!empty($dbExp)) {
            $output .= "Paliwanag: {$dbExp}\n\n";
        } else {
            $output .= "Paliwanag: Ito ang itinatakda ng opisyal na probisyon at pamantayan ng Civil Service Examination para sa paksang ito.\n\n";
        }

        $output .= "Tandaan: Sa pagsusulit, siguraduhing suriin nang mabuti ang pangunahing probisyon at iwasan ang mga maling opsyon na may kulang o maling detalye.";

        return $this->cleanText($output);
    }
}
