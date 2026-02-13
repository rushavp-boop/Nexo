<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
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
            $page = $request->query('page', 1);

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->get(self::PERENUAL_BASE_URL . '/v2/species-list', [
                    'key' => self::PERENUAL_API_KEY,
                    'page' => $page
                ]);

            if ($response->failed()) {
                \Log::error('Perenual Plants API Error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch plants from API', 'status' => $response->status()], 500);
            }

            $data = $response->json();

            // Validate data structure
            if (!isset($data['data'])) {
                \Log::warning('Unexpected Perenual response structure', ['data' => $data]);
                return response()->json(['error' => 'Invalid API response structure'], 500);
            }

            $plants = collect($data['data'] ?? [])
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
                ->filter(fn($p) => !empty($p['name']))
                ->values();

            return response()->json($plants);
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
