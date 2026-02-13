<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use OpenAI\Factory;

class OpenAIService
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

    public static function generateItinerary(string $destination, string $budget, int $days)
    {
        // Professional prompt
        $prompt = "You are a professional travel planner. Create a detailed $days-day travel itinerary for $destination for a $budget budget.
    Each day should start with arrival or breakfast depending on the day, and divide the activities into: morning, afternoon, evening, and night.
    Each activity should be realistic and suitable for the destination and use longer descriptions not shorter ones(at least 15 words per activity).
    Include realistic daily budget in Nepali Rupees (Rs).
    Output the itinerary in the following strict JSON format:

    {
        \"plan\": [
            {
                \"day\": 1,
                \"desc\": [
                    \"Morning: ...\",
                    \"Afternoon: ...\",
                    \"Evening: ...\",
                    \"Night: ...\"
                ],
                \"budget\": \"Approx. XXXX Rs\"
            },
            ...
        ]
    }";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        // Parse JSON if returned, fallback to raw content
        return json_decode($content, true) ?: ['plan' => $content];
    }


    public static function searchHotels(string $destination)
    {
        $prompt = "List 5 luxury/mid-range hotels in $destination with realistic details. Return as JSON array with objects containing: name (hotel name), rating (4.0-5.0), location (area in city), amenities (array of 3-5 amenities), pricePerNight (realistic daily rate in Nepali Rupees, typically 2000-15000 range). Make prices realistic for Nepal tourism.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = json_decode($content, true);

        if (!$result || !is_array($result)) {
            return [
                ['name' => 'Hotel Himalaya', 'rating' => 4.5, 'location' => 'Thamel', 'amenities' => ['WiFi', 'Restaurant', 'Spa'], 'pricePerNight' => 3500],
                ['name' => 'Kathmandu Guest House', 'rating' => 4.2, 'location' => 'Thamel', 'amenities' => ['WiFi', 'Breakfast', 'Gym'], 'pricePerNight' => 2800],
            ];
        }

        return $result;
    }

    public static function searchCars(string $destination)
    {
        $prompt = "List 5 cars available for rent in $destination with name, type, price per day, specs, and image URL in JSON format.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        return json_decode($content, true) ?: [];
    }

    public static function analyzeSymptoms(string $symptoms): array
    {
        $prompt = "Based on these symptoms: '$symptoms', provide a JSON response with: diagnosis (brief professional diagnosis), specialist (recommended doctor type), hospitals (array of 3 hospitals in Nepal), urgency (Low/Medium/High). Include medical disclaimer.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = json_decode($content, true);

        if (!$result) {
            return [
                'diagnosis' => 'Please consult a healthcare professional',
                'specialist' => 'General Practitioner',
                'hospitals' => ['TUTH Central', 'Medicity Hospital', 'Rescue Center'],
                'urgency' => 'Medium'
            ];
        }

        return $result;
    }

    public static function analyzeAgriLocation(string $location, string $crop): array
    {
        $prompt = "Analyze the location '$location' for growing '$crop' in Nepal. Provide JSON with: suitability (Excellent/Good/Fair/Poor), bestVariety (recommended crop variety), soilTips (soil preparation advice), climateRisk (climate-related risks and mitigation).";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = json_decode($content, true);

        if (!$result) {
            return [
                'suitability' => 'Moderate',
                'bestVariety' => 'Local variety recommended',
                'soilTips' => 'Use organic compost and ensure proper drainage',
                'climateRisk' => 'Check local weather patterns'
            ];
        }

        return $result;
    }

    public static function translatePlantData(string $commonName): array
    {
        $prompt = "Provide Nepali translation and brief cultivation guide for the plant named: $commonName. Return a JSON object with keys: nepaliName (string), guide (short paragraph), altitude (suggested altitude as string).";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = json_decode($content, true);

        if (!$result || !is_array($result)) {
            return [
                'nepaliName' => '',
                'guide' => 'No localized guide available.',
                'altitude' => 'Varies'
            ];
        }

        return $result;
    }

    public static function generateTravelHealthTips(string $destination): array
    {
        $prompt = "Generate 3 specific, practical health and safety tips for traveling to $destination. Return as JSON array with objects containing: tip (the advice), icon (emoji), and category (Altitude/Water/Food/Disease/Weather/Safety). Make them relevant and actionable.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = json_decode($content, true);

        if (!$result || !is_array($result)) {
            return [
                ['tip' => 'Stay hydrated throughout your journey', 'icon' => '💧', 'category' => 'Water'],
                ['tip' => 'Consult a doctor before traveling for vaccinations', 'icon' => '💉', 'category' => 'Disease'],
                ['tip' => 'Pack a basic first aid kit and travel insurance', 'icon' => '🏥', 'category' => 'Safety']
            ];
        }

        return $result;
    }

    public static function generateDoctorRecommendations(string $diagnosis, string $urgency): array
    {
        $prompt = "Based on the diagnosis '$diagnosis' with urgency level '$urgency', recommend 3 suitable doctors in Nepal. Return as JSON array with objects containing: name (full name), specialty (medical specialty), hospital (hospital name), experience (years), phone (contact), availability (availability status). Make recommendations appropriate for the urgency level.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = json_decode($content, true);

        if (!$result || !is_array($result)) {
            return [
                ['name' => 'Dr. Binod Poudel', 'specialty' => 'General Medicine', 'hospital' => 'TUTH Central', 'experience' => '15 years', 'phone' => '+977-1-XXXXX', 'availability' => '24/7'],
                ['name' => 'Dr. Sita Gurung', 'specialty' => 'Emergency Medicine', 'hospital' => 'Rescue Center', 'experience' => '12 years', 'phone' => '+977-1-XXXXX', 'availability' => '24/7'],
                ['name' => 'Dr. Amit Shah', 'specialty' => 'Internal Medicine', 'hospital' => 'Medicity Hospital', 'experience' => '10 years', 'phone' => '+977-1-XXXXX', 'availability' => 'Mon-Sun 9AM-9PM']
            ];
        }

        return $result;
    }

    /**
     * Chat with context-aware responses about Nexa app only
     */
    public static function chat(array $messages): string
    {
        $systemPrompt = "You are Nexo.Chat, an AI assistant for the NEXO.GLOBAL platform. Your role is to help users understand and use the Nexo application features, and help in daily life.

IMPORTANT RULES:
1. ONLY answer questions related to EDUCATION AND NORMAL QUERIES. REMEMBER THAT KIDS FROM AGE 9-17 WILL BE USING THIS. HENCE FILTER CONTENT AS REQUIRED
2. The platform includes these modules:
   - Travel/Voyage: Plan trips, book hotels, rent cars
   - Health: Health checks, symptom analysis, doctor recommendations, travel health tips
   - Agro: Plant information, disease analysis, crop recommendations, location-based agricultural advice
   - Education: Generate lessons, answer academic questions for Nepal NEB curriculum
   - Calender, Notes, Reminders, and basic app navigation
   - Weather information and daily weather updates, including other climatic data
3. If asked about anything inappropriate, politely redirect: 'I'm designed to help with Nexo platform features only. How can I assist you with Travel, Health, Education, or Agro modules?'
4. Be helpful, concise, and professional
5. Use the conversation history to provide contextual responses

Keep responses relevant to the Nexo platform and its features. However, if the user asks about anything outside nexo, to help with daily tasks, please do so.";

        $client = self::getClient();

        // Prepare messages with system prompt
        $chatMessages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Filter and validate messages (last 15 messages to maintain context)
        $recentMessages = array_slice($messages, -15);
        foreach ($recentMessages as $msg) {
            // Ensure message has required fields and valid role
            if (
                isset($msg['role']) && isset($msg['content']) &&
                in_array($msg['role'], ['user', 'assistant', 'system'])
            ) {
                // Skip system messages as we already have one
                if ($msg['role'] !== 'system') {
                    // Map 'model' role to 'assistant' for OpenAI API
                    $role = $msg['role'] === 'model' ? 'assistant' : $msg['role'];
                    $chatMessages[] = [
                        'role' => $role,
                        'content' => $msg['content']
                    ];
                }
            }
        }

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $chatMessages,
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

            if (isset($response->choices[0]->message->content)) {
                return trim($response->choices[0]->message->content);
            }

            return 'I apologize, but I couldn\'t generate a response. Please try again.';
        } catch (\Exception $e) {
            Log::error('OpenAI Chat Error: ' . $e->getMessage(), [
                'exception' => $e,
                'messages_count' => count($chatMessages)
            ]);

            return 'I\'m having trouble connecting right now. Please try again later or contact support if the issue persists.';
        }
    }
}
