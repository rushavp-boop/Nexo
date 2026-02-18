<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use OpenAI\Factory;

class EduService
{
    private static function getClient(): Client
    {
        return (new Factory())
            ->withApiKey(config('openai.api_key'))
            ->withHttpClient(new \GuzzleHttp\Client([
                'verify' => config('openai.verify', false),
                'timeout' => config('openai.request_timeout', 30),
            ]))
            ->make();
    }

    /**
     * Extract and parse JSON from API response.
     * Handles markdown code blocks and other common formatting issues.
     */
    private static function parseJSON(string $content): ?array
    {
        // Remove markdown code blocks
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/', $content, $matches)) {
            $content = trim($matches[1]);
        }

        // Attempt JSON decode
        $result = json_decode($content, true);

        if (!$result) {
            Log::warning('JSON Parse Failure in EduService', [
                'content' => substr($content, 0, 200),
                'json_error' => json_last_error_msg()
            ]);
        }

        return $result;
    }

    public static function generateEduResponse(int $grade, string $subject, string $topic): array
    {
        $prompt = "You are a master teacher for the Nepal NEB curriculum.

Create a comprehensive, full-length lesson for Grade {$grade} {$subject} on the topic: \"{$topic}\".
The lesson must be detailed enough to cover a 45-minute class.

Return ONLY valid JSON in the following structure:

{
  \"concept\": \"string\",
  \"objectives\": [\"string\"],
  \"explanation\": \"string\",
  \"nepalContext\": \"string\",
  \"keyPoints\": [\"string\"],
  \"analogy\": \"string\",
  \"questions\": [
    {
      \"question\": \"string\",
      \"options\": [\"string\", \"string\", \"string\", \"string\"],
      \"correct_answer\": 0
    }
  ],
  \"followUpSuggestions\": [\"string\"]
}

Rules:
- Exactly 5 questions
- Options must be meaningful (not Option A/B)
- correct_answer MUST be the index (0, 1, 2, or 3) of the correct option
  - If option 1 is correct: correct_answer = 0
  - If option 2 is correct: correct_answer = 1
  - If option 3 is correct: correct_answer = 2
  - If option 4 is correct: correct_answer = 3
- No markdown
- No explanation outside JSON";

        try {
            $client = self::getClient();
            $response = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            $content = $response->choices[0]->message->content;
            $result = self::parseJSON($content);

            if (
                $result &&
                isset($result['concept'], $result['questions']) &&
                is_array($result['questions'])
            ) {
                return [
                    'title' => $result['concept'],
                    'description' => "Comprehensive lesson on {$topic} for Grade {$grade} {$subject}",
                    'objectives' => $result['objectives'] ?? [],
                    'content' => $result['explanation'] ?? '',
                    'nepal_context' => $result['nepalContext'] ?? '',
                    'key_points' => $result['keyPoints'] ?? [],
                    'analogy' => $result['analogy'] ?? '',
                    'questions' => array_values(array_filter(
                        $result['questions'],
                        fn($q) =>
                        isset($q['question'], $q['options'], $q['correct_answer']) &&
                            is_array($q['options']) &&
                            count($q['options']) === 4
                    )),
                    'follow_up_suggestions' => $result['followUpSuggestions'] ?? []
                ];
            }
        } catch (\Exception $e) {
            Log::error('OpenAI API error in generateEduResponse: ' . $e->getMessage());
        }

        // Fallback to syllabus content if API fails
        return self::getFallbackLesson($grade, $subject, $topic);
    }

    public static function eduFollowUp(string $context, string $question): string
    {
        $prompt = "Based on this lesson context: \"{$context}\", answer this student follow-up question: \"{$question}\". Keep the explanation simple, encouraging, and accurate to the NEB syllabus. Provide a helpful and educational response.";

        try {
            $client = self::getClient();
            $response = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            return $response->choices[0]->message->content ?: "I'm sorry, I couldn't process that question. Could you try rephrasing?";
        } catch (\Exception $e) {
            Log::error('OpenAI API error in eduFollowUp: ' . $e->getMessage());
            return "I'm sorry, I couldn't process that question right now. Please try again later.";
        }
    }

    private static function getFallbackLesson(int $grade, string $subject, string $topic): array
    {
        return [
            'title' => ucfirst($topic),
            'description' => "Learn about {$topic} in {$subject} for Grade {$grade}",
            'objectives' => [
                "Understand the basic concepts of {$topic}",
                "Learn key principles and applications",
                "Apply knowledge to solve problems"
            ],
            'content' => "<h3>{$topic}</h3><p>This topic covers important concepts in {$subject} for Grade {$grade} students. The content is designed to help you understand the fundamental principles and their applications.</p><p>Key learning points include:</p><ul><li>Understanding basic concepts</li><li>Learning important definitions</li><li>Exploring real-world applications</li><li>Practicing problem-solving skills</li></ul>",
            'nepal_context' => "This topic relates to Nepal through our geography, culture, and daily life. Nepal's diverse landscape and rich cultural heritage provide excellent examples for understanding {$topic}.",
            'key_points' => ["Study regularly", "Practice problems", "Ask questions when needed"],
            'analogy' => "Think of {$topic} like the diverse landscapes of Nepal - from the high Himalayas to the fertile Terai plains, each part has its own unique characteristics and importance.",
            'questions' => [
                ['question' => "What is the main concept of {$topic}?", 'options' => ['Basic idea', 'Advanced concept', 'Simple definition', 'Complex theory'], 'correct_answer' => 'Basic idea'],
                ['question' => "Why is {$topic} important to study?", 'options' => ['For exams', 'For understanding', 'For knowledge', 'All of these'], 'correct_answer' => 'All of these'],
                ['question' => "How can you apply {$topic} in daily life?", 'options' => ['In school', 'At home', 'Everywhere', 'Nowhere'], 'correct_answer' => 'Everywhere']
            ],
            'follow_up_suggestions' => ["Practice more examples", "Discuss with classmates", "Ask teacher for clarification"]
        ];
    }
    /* ===========================
       AI QUIZ (25 QUESTIONS)
    ============================ */
    public static function generateQuiz(int $grade, string $topic): array
    {
        $prompt = <<<PROMPT
Generate 25 multiple-choice quiz questions that get harder as you progress. Grade: {$grade}, Topic: {$topic}

CRITICAL REQUIREMENTS:
- Questions must be from NEB Nepal curriculum (grade 3-10)
- Exactly 4 options per question
- Set correct_answer to the INDEX of the correct option (0, 1, 2, or 3)
- Increasing difficulty throughout
- Ensure each question is distinct and tests understanding

Return ONLY this exact JSON format (no markdown, no extra text):
[
  {
    "question": "Clear question text",
    "options": ["First option text", "Second option text", "Third option text", "Fourth option text"],
    "correct_answer": 0
  }
]

IMPORTANT:
- correct_answer MUST be a number (0-3) NOT a letter
- If option 1 is correct, correct_answer=0
- If option 2 is correct, correct_answer=1
- If option 3 is correct, correct_answer=2
- If option 4 is correct, correct_answer=3
PROMPT;

        try {
            $res = self::getClient()->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 2500
            ]);

            $result = self::parseJSON($res->choices[0]->message->content);
            return $result ?: [];
        } catch (\Throwable $e) {
            Log::error('Quiz Generation Error: ' . $e->getMessage());
            return [];
        }
    }

    /* ===========================
       AI FLASH CARDS (25)
    ============================ */
    public static function generateFlashCards(int $grade, string $topic): array
    {
        $prompt = <<<PROMPT
Generate 25 educational flash cards, they should be invormative and advanced as they progress.

Grade: {$grade}
Topic: {$topic}

Rules:
- NEB Curriculum aligned (from grade 3-10).
- STRICLY GIVE FLASHCARDS FROM NEPAL CURRICULUM ONLY.
- Simple front, clear back
- Exam focused
- Concept clarity

Return STRICT JSON:

[
 {
  "front": "question / term",
  "back": "clear explanation"
 }
]
PROMPT;

        try {
            $res = self::getClient()->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 2500
            ]);

            $result = self::parseJSON($res->choices[0]->message->content);
            return $result ?: [];
        } catch (\Throwable $e) {
            Log::error('FlashCard Generation Error: ' . $e->getMessage());
            return [];
        }
    }
}

