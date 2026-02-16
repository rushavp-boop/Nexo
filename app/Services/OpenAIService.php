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
        $prompt = "You are a Nepal tourism expert. List 5 real, verified hotels in $destination, Nepal. Use actual hotel names from Google Maps or Booking.com.

    For EACH hotel provide:
    - name: Actual hotel name (verify it exists in $destination)
    - rating: Real rating (4.0-5.0) based on common review platforms
    - location: Specific area/neighborhood in $destination with nearby landmarks
    - amenities: Array of 4-6 realistic amenities (WiFi, Hot Water, Restaurant, Mountain View, Airport Pickup, Parking, etc.)
    - pricePerNight: Realistic price in Nepali Rupees based on actual Nepal hotel rates

    Price guidelines for Nepal:
    - Budget hotels: Rs 1200-2500/night
    - Mid-range: Rs 2500-5500/night
    - Luxury: Rs 5500-15000+/night

    Include a mix of price ranges. Return ONLY valid JSON array (no markdown, no extra text).
    Example: [{\"name\":\"Hotel Himalaya\",\"rating\":4.5,\"location\":\"Kupondole, near Jawalakhel\",\"amenities\":[\"WiFi\",\"Restaurant\",\"Spa\",\"Garden\"],\"pricePerNight\":4200}]";

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
        $prompt = "You are a medical advisor for Nepal. Analyze these symptoms: '$symptoms' and provide a detailed, helpful response.

    Provide a JSON response with:
    - diagnosis: A 3-4 sentence professional assessment. Be thorough but avoid scaring the patient. Mention possible conditions and what symptoms suggest. Include medical disclaimer.
    - specialist: Specific type of doctor needed (e.g., 'Cardiologist', 'ENT Specialist', 'General Physician', 'Gastroenterologist')
    - hospitals: Array of 3 real, reputable hospitals in Nepal (major cities: Kathmandu, Pokhara, Chitwan) that have relevant departments
    - urgency: Low/Medium/High based on symptom severity

    Consider Nepal's healthcare context:
    - Mention if emergency care is needed
    - Reference common health issues in Nepal (altitude sickness, gastroenteritis, seasonal flu, etc.)
    - Be culturally sensitive and practical

    Return ONLY valid JSON (no markdown). Always include disclaimer about consulting a licensed medical professional.";

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
        $prompt = "Analyze the location '$location' for growing '$crop' in Nepal. Provide ONLY valid JSON (no markdown, no extra text). Use these keys: suitability (Excellent/Good/Fair/Poor), bestVariety, soilTips, fertilizersToBeUsed, growthProtocol, climateRisk. Make each value clear, practical, and longer (2-4 sentences each). Avoid vague statements. Include actionable steps, timing guidance, and quantities where reasonable (e.g., per ropani/hectare or per plant). Use simple Nepali-English mixed phrasing that an average Nepali farmer can understand. Keep it realistic for Nepal's climate and farming practices.";

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
        $prompt = "You are a Nepal travel health expert. Generate 3 highly specific, actionable health and safety tips for traveling to $destination in Nepal.

    For EACH tip provide:
    - tip: 50-80 words of detailed, practical advice. Include specific actions, timings, what to bring, and why it matters.
    - icon: Relevant emoji (💧🏔️🍜💊🌡️⚠️🏥🧊🥾🧴)
    - category: One of: Altitude, Water, Food, Disease, Weather, Safety, Medication, Gear

    Tips should be:
    - Location-specific (e.g., altitude advice for high-elevation areas like Everest Base Camp, Annapurna; water safety for Terai; cold weather prep for winter mountain travel)
    - Actionable with clear steps (e.g., 'Start Diamox 250mg 24hrs before ascent', 'Boil water for 5 minutes or use purification tablets')
    - Include brand names, costs, or local availability where helpful

    Consider Nepal-specific risks: altitude sickness above 2500m, waterborne diseases, seasonal weather extremes, trekking injuries.
    Return ONLY valid JSON array (no markdown).";

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
        $prompt = "You are a Nepal healthcare directory expert. Based on diagnosis '$diagnosis' with urgency '$urgency', recommend 3 suitable, real doctors practicing in Nepal.

    For EACH doctor provide:
    - name: Full name (use realistic Nepali names like Dr. Rajesh Sharma, Dr. Anjali Thapa, Dr. Binod K.C.)
    - specialty: Specific medical specialty matching the diagnosis
    - hospital: Real hospital name in Nepal (e.g., TUTH, Tribhuvan University Teaching Hospital, Medicity, Grande International, B&B Hospital, Kathmandu Medical College, etc.)
    - experience: Years of practice (8-25 years range)
    - phone: Format +977-1-XXXXXX or +977-98XXXXXXXX (use placeholder X's)
    - availability: Realistic schedule based on urgency (24/7 for High urgency, specific hours for Low/Medium)

    Match recommendations to urgency:
    - High: Emergency specialists, 24/7 hospitals, immediate availability
    - Medium: General/specialist doctors, same-day or next-day availability
    - Low: Scheduled appointments, outpatient clinics

    Return ONLY valid JSON array (no markdown). Use real hospital names from Kathmandu, Pokhara, or other major cities.";

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
