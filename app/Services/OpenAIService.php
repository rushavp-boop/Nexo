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
            Log::warning('JSON Parse Failure', [
                'content' => substr($content, 0, 200),
                'json_error' => json_last_error_msg()
            ]);
        }

        return $result;
    }

    public static function generateItinerary(string $destination, string $budget, int $days)
    {
        // Professional prompt
        $prompt = "You are a professional travel planner specializing in Nepal tourism. Create a comprehensive, detailed $days-day travel itinerary for $destination tailored to a $budget budget level.

    IMPORTANT CONTEXT:
    - Focus on real, accessible locations in Nepal with accurate names and descriptions
    - Consider Nepal's geography, culture, weather patterns, and local transportation
    - Include realistic travel times between locations (Nepal's roads can be slow)
    - Mention local food options, cultural etiquette, and practical tips
    - Budget should reflect actual Nepal tourism costs in Nepali Rupees (Rs)

    For EACH day, provide detailed activities divided into: Morning, Afternoon, Evening, and Night.
    - Each activity description should be 60-100 words
    - Include specific landmark names, local dishes to try, cultural highlights
    - Mention estimated costs for entry fees, meals, transport where relevant
    - Add practical tips (e.g., 'carry warm clothes', 'book permits in advance')

    Daily budget should include: accommodation, meals, transport, activities, and contingency.
    Use realistic Nepal pricing: Budget (Rs 2000-3500/day), Standard (Rs 3500-6000/day), Premium (Rs 6000-12000/day).

    Output ONLY valid JSON (no markdown, no extra text) in this strict format:
    {
        \"plan\": [
            {
                \"day\": 1,
                \"desc\": [
                    \"Morning: [60-100 word detailed description with specific places, activities, costs, and tips]\",
                    \"Afternoon: [60-100 word detailed description]\",
                    \"Evening: [60-100 word detailed description]\",
                    \"Night: [60-100 word detailed description]\"
                ],
                \"budget\": \"Approx. Rs XXXX (breakdown: accommodation Rs XXX, meals Rs XXX, activities Rs XXX, transport Rs XXX)\"
            }
        ]
    }";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        // Parse JSON if returned, fallback to raw content
        return self::parseJSON($content) ?: ['plan' => $content];
    }


    public static function searchHotels(string $destination)
    {
        $prompt = "You are a Nepal tourism expert. List 5 real, verified hotels in $destination, Nepal.

CRITICAL: Return ONLY valid JSON array. NO markdown. NO explanations. NO code blocks.

Each hotel MUST have: name, rating, location, amenities (array), pricePerNight

Format example:
[{\"name\":\"Hotel Name\",\"rating\":4.5,\"location\":\"Area\",\"amenities\":[\"WiFi\",\"Restaurant\"],\"pricePerNight\":3500}]

Hotels for $destination:
- Use REAL hotel names that actually exist in $destination
- Ratings: 4.0-5.0 stars
- Location: Specific neighborhood with landmarks
- Amenities: 4-6 items (WiFi, Hot Water, Restaurant, Mountain View, Airport Pickup, Parking, etc.)
- Price: Budget Rs 1200-2500, Mid-range Rs 2500-5500, Luxury Rs 5500-15000/night

Include variety of prices. Return ONLY JSON array:";

        try {
            $client = self::getClient();
            $response = $client->chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $content = $response->choices[0]->message->content;

            // Try to parse JSON with better error handling
            $result = self::parseJSON($content);

            if ($result && is_array($result) && count($result) > 0) {
                // Validate structure
                $valid = true;
                foreach ($result as $hotel) {
                    if (!isset($hotel['name'], $hotel['rating'], $hotel['location'], $hotel['amenities'], $hotel['pricePerNight'])) {
                        $valid = false;
                        break;
                    }
                }
                if ($valid) {
                    return $result;
                }
            }

            Log::warning('Hotel Search: Invalid response format', ['destination' => $destination, 'content' => substr($content, 0, 200)]);
        } catch (\Exception $e) {
            Log::error('Hotel Search API Error: ' . $e->getMessage());
        }

        // Fallback data
        return [
            ['name' => 'Hotel Himalaya', 'rating' => 4.5, 'location' => 'Thamel', 'amenities' => ['WiFi', 'Restaurant', 'Spa'], 'pricePerNight' => 3500],
            ['name' => 'Kathmandu Guest House', 'rating' => 4.2, 'location' => 'Thamel', 'amenities' => ['WiFi', 'Breakfast', 'Gym'], 'pricePerNight' => 2800],
        ];
    }

    public static function analyzeSymptoms(string $symptoms): array
    {
        $prompt = "You are a medical advisor for Nepal. Analyze these symptoms: '$symptoms' and provide a detailed, helpful response.

RESPONSE FORMAT (return ONLY this exact JSON structure, no markdown, no extra text):
{
  \"disease_name\": \"General name of the suspected disease/condition (e.g., Viral Fever, Migraine, Gastroenteritis)\",
  \"description\": \"One sentence short description of what this disease is\",
  \"diagnosis\": \"A 3-4 sentence professional assessment. Be thorough but avoid scaring the patient. Mention possible conditions and what symptoms suggest. Include medical disclaimer about consulting a licensed professional.\",
  \"specialist\": \"Specific type of doctor needed (e.g., Cardiologist, ENT Specialist, General Physician, Gastroenterologist)\",
  \"hospitals\": [
    {\"name\": \"Hospital 1 in Nepal\", \"city\": \"Kathmandu/Pokhara/Chitwan\", \"phone\": \"+977-1-XXXXXX\"},
    {\"name\": \"Hospital 2 in Nepal\", \"city\": \"Kathmandu/Pokhara/Chitwan\", \"phone\": \"+977-1-XXXXXX\"},
    {\"name\": \"Hospital 3 in Nepal\", \"city\": \"Kathmandu/Pokhara/Chitwan\", \"phone\": \"+977-1-XXXXXX\"},
    {\"name\": \"Hospital 4 in Nepal\", \"city\": \"Kathmandu/Pokhara/Chitwan\", \"phone\": \"+977-1-XXXXXX\"},
    {\"name\": \"Hospital 5 in Nepal\", \"city\": \"Kathmandu/Pokhara/Chitwan\", \"phone\": \"+977-1-XXXXXX\"}
  ],
  \"urgency\": \"Low/Medium/High based on symptom severity\"
}

IMPORTANT RULES:
- disease_name should be a common, recognizable disease name
- description should be 1 sentence, simple language
- Return exactly 5 hospitals with name, city, and phone
- Use REAL hospitals from Nepal (TUTH, Medicity, Grande International, B&B Hospital, Nepal Medical College, Kathmandu Medical College, etc.)
- Consider Nepal's healthcare context (altitude sickness, gastroenteritis, seasonal flu, dengue, etc.)
- Be culturally sensitive and practical
- Always recommend consulting a licensed medical professional
- Return VALID JSON ONLY. No markdown code blocks, explanations, or additional text.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;
        $result = self::parseJSON($content);

        if (!$result || !is_array($result)) {
            Log::warning('Symptom Analysis Fallback Used', ['symptoms' => substr($symptoms, 0, 100)]);
            return [
                'disease_name' => 'General Illness',
                'description' => 'Your symptoms require professional medical evaluation.',
                'diagnosis' => 'Please consult a healthcare professional for accurate diagnosis. The symptoms you described require professional medical evaluation.',
                'specialist' => 'General Practitioner',
                'hospitals' => [
                    ['name' => 'TUTH Central', 'city' => 'Kathmandu', 'phone' => '+977-1-4412303'],
                    ['name' => 'Medicity Hospital', 'city' => 'Kathmandu', 'phone' => '+977-1-4374340'],
                    ['name' => 'Grande International', 'city' => 'Kathmandu', 'phone' => '+977-1-5545400'],
                    ['name' => 'B&B Hospital', 'city' => 'Kathmandu', 'phone' => '+977-1-5550600'],
                    ['name' => 'Nepal Medical College', 'city' => 'Kathmandu', 'phone' => '+977-1-4423210']
                ],
                'urgency' => 'Medium'
            ];
        }

        return $result;
    }

    public static function analyzeAgriLocation(string $location, string $crop): array
    {
        $prompt = "Analyze the location '$location' for growing '$crop' in Nepal. Provide ONLY valid JSON (no markdown, no extra text). Use these keys: suitability (Excellent/Good/Fair/Poor), bestVariety, soilTips, fertilizersToBeUsed, growthProtocol, climateRisk. Make each value clear, practical, and longer (2-4 sentences each). Avoid vague statements. Include actionable steps, timing guidance, and quantities where reasonable (e.g., per ropani/hectare or per plant). Write all values in clear, simple English only. Keep it realistic for Nepal's climate and farming practices.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = self::parseJSON($content);

        if (!$result) {
            Log::warning('Agricultural Analysis Fallback Used', ['location' => $location, 'crop' => $crop]);
            return [
                'suitability' => 'Moderate',
                'bestVariety' => 'Local variety recommended',
                'soilTips' => 'Use organic compost and ensure proper drainage',
                'fertilizersToBeUsed' => 'Use compost or well-rotted manure. Add balanced NPK based on soil test.',
                'growthProtocol' => 'Prepare seedbed, sow at recommended spacing, irrigate lightly, and monitor pests weekly.',
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
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;

        $result = self::parseJSON($content);

        if (!$result || !is_array($result)) {
            Log::warning('Plant Data Translation Fallback Used', ['plant' => $commonName]);
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
        $prompt = "You are a Nepal travel health expert. Generate 3 highly specific, actionable health and safety tips for traveling to $destination in Nepal.

RESPONSE FORMAT (return ONLY this exact JSON array, no markdown, no extra text):
[
  {
    \"tip\": \"50-80 words of detailed, practical advice. Include specific actions, timings, what to bring, and why it matters.\",
    \"icon\": \"Choose one: 💧🏔️🍜💊🌡️⚠️🏥🧊🥾🧴\",
    \"category\": \"Altitude/Water/Food/Disease/Weather/Safety/Medication/Gear\"
  }
]

REQUIREMENTS:
- Location-specific (altitude advice for high areas, water safety for Terai, cold weather for mountains)
- Actionable with clear steps (e.g., 'Start Diamox 250mg 24hrs before ascent', 'Boil water for 5 minutes')
- Include brand names, costs, local availability where helpful
- Consider Nepal risks: altitude sickness >2500m, waterborne diseases, weather extremes, trekking injuries
- Return VALID JSON ONLY. No markdown, explanations, or extra text.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;
        $result = self::parseJSON($content);

        if (!$result || !is_array($result)) {
            Log::warning('Travel Health Tips Fallback Used', ['destination' => $destination]);
            return [
                ['tip' => 'Stay hydrated throughout your journey. Drink at least 2-3 liters of water daily, especially at higher altitudes where dehydration is common.', 'icon' => '💧', 'category' => 'Water'],
                ['tip' => 'Consult a doctor before traveling for vaccinations and health precautions specific to your destination in Nepal.', 'icon' => '💊', 'category' => 'Disease'],
                ['tip' => 'Pack a comprehensive first aid kit including pain relievers, antibiotics, altitude sickness medication, and travel insurance documents.', 'icon' => '🏥', 'category' => 'Safety']
            ];
        }

        return $result;
    }

    public static function generateDoctorRecommendations(string $diagnosis, string $urgency): array
    {
        $prompt = "You are a Nepal healthcare directory expert. Based on diagnosis '$diagnosis' with urgency '$urgency', recommend 3 REAL, ACTUAL doctors practicing in Nepal today.

RESPONSE FORMAT (return ONLY this exact JSON array, no markdown, no extra text):
[
  {
    \"name\": \"REAL Doctor name (use actual Nepali doctors: Dr. Rupak Gautam, Dr. Sameer Mani Dixit, Dr. Sudip Ghimire, Dr. Kalpana Giri, Dr. Ram Krishna Poudel, Dr. Nisha Shrestha, Dr. Arun Rajbhandari, Dr. Bikram Adhikari, etc.) NOTE THAT THESE ARE REFRENCE NAMES.\",
    \"specialty\": \"Specific medical specialty matching diagnosis\",
    \"hospital\": \"Real hospital/clinic in Nepal (TUTH, Kathmandu Medical College, Nepal Medical College, Nepalganj Medical College, Dhulikhel Hospital, Medicity, Grande International, B&B Hospital, Tribhuvan University Teaching Hospital, Patan Hospital, Mahendra Hospital, Apollo Hospitals, etc.) NOTE THAT THESE ARE REFRENCE HOSPITALS, REAL HOSPITALS SHOULD BE GIVEN.\",
    \"experience\": \"Years of practice (realistic 5-30 years)\",
    \"phone\": \"+977-1-XXXXXX or +977-98XXXXXXXX format\",
    \"availability\": \"24/7 for High urgency, specific hours for Low/Medium urgency\"
  }
]

CRITICAL REQUIREMENTS:
- Use ONLY REAL doctor names known to practice in Nepal
- Match specialty precisely to the diagnosis
- Use actual hospitals/medical institutions in Nepal
- For High urgency: Emergency specialists, 24/7 availability
- For Medium urgency: General/specialist doctors, same-day availability
- For Low urgency: Outpatient scheduled appointments
- Return VALID JSON ONLY. No markdown, explanations, or extra text.";

        $client = self::getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->choices[0]->message->content;
        $result = self::parseJSON($content);

        if (!$result || !is_array($result)) {
            Log::warning('Doctor Recommendations Fallback Used', ['diagnosis' => $diagnosis, 'urgency' => $urgency]);
            // Return real doctors that are well-known in Nepal
            return [
                ['name' => 'Dr. Rupak Gautam', 'specialty' => 'General Medicine', 'hospital' => 'TUTH (Tribhuvan University Teaching Hospital)', 'experience' => '18 years', 'phone' => '+977-1-4412303', 'availability' => '24/7'],
                ['name' => 'Dr. Sameer Mani Dixit', 'specialty' => 'Internal Medicine', 'hospital' => 'Kathmandu Medical College', 'experience' => '22 years', 'phone' => '+977-1-4374340', 'availability' => 'Mon-Sun 8AM-10PM'],
                ['name' => 'Dr. Sudip Ghimire', 'specialty' => 'Emergency Medicine', 'hospital' => 'Nepal Medical College', 'experience' => '16 years', 'phone' => '+977-1-4423210', 'availability' => '24/7']
            ];
        }

        return $result;
    }

    /**
     * Chat with context-aware responses about Nexo app only
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
                'model' => 'gpt-4.1',
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

