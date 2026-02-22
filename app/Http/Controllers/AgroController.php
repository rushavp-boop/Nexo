<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\Price;
use Illuminate\Support\Facades\Http;

class AgroController extends Controller
{
    private const PERENUAL_API_KEY = 'sk-WiBX6960c83ca308114259';
    private const PERENUAL_BASE_URL = 'https://perenual.com/api';

    public function index()
    {
        return view('user.agro');
    }

    public function getPlants(Request $request)
    {
        try {
            // Fetch multiple pages to get ~100 plants (API returns 30 per page)
            $allPlants = collect();

            for ($page = 1; $page <= 4; $page++) {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->get(self::PERENUAL_BASE_URL . '/v2/species-list', [
                        'key' => self::PERENUAL_API_KEY,
                        'page' => $page
                    ]);

                if ($response->failed()) {
                    \Log::error('Perenual Plants API Error', ['status' => $response->status(), 'page' => $page]);
                    break;
                }

                $data = $response->json();

                if (!isset($data['data']) || empty($data['data'])) {
                    break;
                }

                $plants = collect($data['data'])
                    ->map(function($plant) {
                        $id = $plant['id'] ?? null;
                        $name = $plant['common_name'] ?? null;

                        // scientific_name can be array or string
                        $scientific_name = '';
                        if (isset($plant['scientific_name'])) {
                            if (is_array($plant['scientific_name'])) {
                                $scientific_name = $plant['scientific_name'][0] ?? '';
                            } else {
                                $scientific_name = $plant['scientific_name'];
                            }
                        }

                        $cycle = $plant['cycle'] ?? 'Unknown';
                        $watering = $plant['watering'] ?? 'Moderate';

                        // sunlight may be missing, string, or array
                        $sunlight = 'Partial Shade';
                        if (array_key_exists('sunlight', $plant)) {
                            if (is_array($plant['sunlight'])) {
                                $sunlight = $plant['sunlight'][0] ?? $sunlight;
                            } elseif (!empty($plant['sunlight'])) {
                                $sunlight = $plant['sunlight'];
                            }
                        }

                        $image = data_get($plant, 'default_image.original_url');

                        return [
                            'id' => $id,
                            'name' => $name ?? ($scientific_name ?: 'Unknown'),
                            'scientific_name' => $scientific_name,
                            'cycle' => $cycle,
                            'watering' => $watering,
                            'sunlight' => $sunlight,
                            'image' => $image,
                        ];
                    })
                    ->filter(fn($p) => !empty($p['name']));

                $allPlants = $allPlants->merge($plants);
            }

            return response()->json($allPlants->values()->take(100));
        } catch (\Exception $e) {
            \Log::error('Get Plants Exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPlantDetails(Request $request, $id)
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->get(self::PERENUAL_BASE_URL . '/v2/species/details/' . $id, [
                    'key' => self::PERENUAL_API_KEY
                ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Plant not found'], 404);
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMarketPrices(Request $request)
    {
        try {
            $search = $request->query('search', '');

            $today = now()->format('Y-m-d');
            $currentDate = Price::where('price_date', $today)->exists()
                ? $today
                : Price::max('price_date');

            if (!$currentDate) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'priceChanges' => [],
                    'lastUpdated' => now()->format('Y-m-d H:i:s')
                ]);
            }

            $currentPrices = Price::where('price_date', $currentDate)->get();
            $previousDate = Price::where('price_date', '<', $currentDate)->max('price_date');
            $previousPrices = $previousDate
                ? Price::where('price_date', $previousDate)->get()->keyBy(fn($price) => strtolower(trim($price->produce_name)))
                : collect();

            $prices = $currentPrices->map(function ($price) {
                return [
                    'nepaliName' => $price->nepali_name ?? $price->produce_name,
                    'englishName' => $price->produce_name,
                    'unit' => $price->unit ?? 'K.G.',
                    'minPrice' => (float) $price->min_price,
                    'maxPrice' => (float) $price->max_price,
                    'avgPrice' => (float) $price->avg_price,
                    'date' => $price->price_date->format('Y-m-d'),
                ];
            })->sortBy('englishName')->values();

            $priceChanges = $currentPrices->map(function ($currentPrice) use ($previousPrices) {
                $key = strtolower(trim($currentPrice->produce_name));
                $previous = $previousPrices->get($key);

                if (!$previous) {
                    return null;
                }

                $previousAvg = (float) $previous->avg_price;
                $currentAvg = (float) $currentPrice->avg_price;

                if ($previousAvg <= 0) {
                    return null;
                }

                $delta = $currentAvg - $previousAvg;
                if (abs($delta) < 0.0001) {
                    return null;
                }

                $percent = abs(($delta / $previousAvg) * 100);

                return [
                    'englishName' => $currentPrice->produce_name,
                    'nepaliName' => $currentPrice->nepali_name ?? $currentPrice->produce_name,
                    'direction' => $delta > 0 ? 'increased' : 'decreased',
                    'changePercent' => round($percent, 2),
                    'currentAvgPrice' => $currentAvg,
                    'previousAvgPrice' => $previousAvg,
                    'currentDate' => $currentPrice->price_date->format('Y-m-d'),
                    'previousDate' => $previous->price_date->format('Y-m-d'),
                ];
            })->filter()->sortByDesc('changePercent')->values();

            // Filter by search if provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $prices = $prices->filter(function ($item) use ($searchLower) {
                    return strpos(strtolower($item['englishName']), $searchLower) !== false ||
                           strpos(strtolower($item['nepaliName']), $searchLower) !== false;
                })->values();

                $priceChanges = $priceChanges->filter(function ($item) use ($searchLower) {
                    return strpos(strtolower($item['englishName']), $searchLower) !== false ||
                           strpos(strtolower($item['nepaliName']), $searchLower) !== false;
                })->values();
            }

            return response()->json([
                'success' => true,
                'data' => $prices,
                'priceChanges' => $priceChanges,
                'lastUpdated' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            \Log::error('Market Prices Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch market prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getCropNepaliName($englishName)
    {
        $translations = [
            'Rice' => 'चावल',
            'Wheat' => 'गहुँ',
            'Maize' => 'मकै',
            'Potato' => 'आलु',
            'Tomato' => 'टमाटर',
            'Onion' => 'प्याज',
            'Garlic' => 'लसुन',
            'Ginger' => 'अदुवा',
            'Lentil' => 'दाल',
            'Chickpea' => 'छोलाभटमास',
            'Bean' => 'बाकुला',
            'Carrot' => 'गाजर',
            'Cabbage' => 'कोबी',
            'Cauliflower' => 'फूलकोबी',
            'Radish' => 'मूला',
            'Spinach' => 'पालेnung',
            'Mustard Greens' => 'सरसो को साग',
            'Cucumber' => 'खीरा',
            'Bottle Gourd' => 'लाउ',
            'Bitter Gourd' => 'करेला',
            'Okra' => 'भिंडी',
            'Chilli' => 'खुर्सानी',
            'Turmeric' => 'हलिमा',
            'Coriander' => 'धनिया',
            'Cumin' => 'जिरा',
        ];
        return $translations[$englishName] ?? $englishName;
    }

    public function getDiseases()
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->get(self::PERENUAL_BASE_URL . '/pest-disease-list', [
                    'key' => self::PERENUAL_API_KEY
                ]);

            if ($response->failed()) {
                \Log::error('Perenual Diseases API Error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch diseases from API', 'status' => $response->status()], 500);
            }

            $data = $response->json();

            // Validate data structure
            if (!isset($data['data'])) {
                \Log::warning('Unexpected Perenual diseases response structure', ['data' => $data]);
                return response()->json(['error' => 'Invalid API response structure'], 500);
            }

            $diseases = collect($data['data'] ?? [])
                ->map(fn($disease) => [
                    'id' => $disease['id'],
                    'name' => $disease['common_name'] ?? 'Unknown',
                    'scientific_name' => $disease['scientific_name'] ?? '',
                    'family' => $disease['family'] ?? '',
                    'description' => $disease['description'] ?? [],
                    'images' => $disease['images'] ?? [],
                ])
                ->values();

            return response()->json($diseases);
        } catch (\Exception $e) {
            \Log::error('Get Diseases Exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|min:2',
            'crop' => 'required|string|min:2',
        ]);

        try {
            $data = OpenAIService::analyzeAgriLocation(
                $validated['location'],
                $validated['crop']
            );
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function translatePlant(Request $request)
    {
        $name = $request->query('name');
        if (!$name) {
            return response()->json(['error' => 'Missing name parameter'], 400);
        }

        try {
            $data = OpenAIService::translatePlantData($name);
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Translate Plant Exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
