<?php

namespace App\Console\Commands;

use App\Models\Price;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncKalimatiPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-kalimati-prices {--date= : The date to sync prices for (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync agricultural prices from Kalimati Market API to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('🌾 Fetching prices from Kalimati API...');

            // Verify SSL for production, skip for development
            $verifySSL = env('APP_ENV') === 'production';

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->get('https://kalimatimarket.gov.np/api/price');

            if (!$response->successful()) {
                $this->error('Failed to fetch from Kalimati API');
                return 1;
            }

            $prices = $response->json() ?? [];

            if (empty($prices)) {
                $this->warning('No prices received from API');
                return 1;
            }

            $priceDate = $this->option('date') ?? now()->format('Y-m-d');
            $syncedCount = 0;

            // Delete today's prices if re-syncing
            Price::where('price_date', $priceDate)->delete();

            foreach ($prices as $price) {
                $produceName = $price['agricultural_produce'] ?? $price['produce_name'] ?? null;

                if (!$produceName) {
                    continue;
                }

                // Parse prices - handle various formats (Rs. 60.00, Rs. 60, etc.)
                $minPrice = $this->parsePrice($price['minimum'] ?? $price['min_price'] ?? 0);
                $maxPrice = $this->parsePrice($price['Maximum'] ?? $price['max_price'] ?? 0);
                $avgPrice = $this->parsePrice($price['average'] ?? $price['avg_price'] ?? 0);

                // If average is missing, calculate it
                if ($avgPrice == 0 && $minPrice > 0 && $maxPrice > 0) {
                    $avgPrice = round(($minPrice + $maxPrice) / 2, 2);
                }

                $unit = $price['Unit'] ?? $price['unit'] ?? 'K.G.';
                $nepaliName = $this->getNepaliName($produceName);

                Price::create([
                    'produce_name' => $produceName,
                    'nepali_name' => $nepaliName,
                    'unit' => $unit,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'avg_price' => $avgPrice,
                    'price_date' => $priceDate,
                ]);

                $syncedCount++;
            }

            $this->info("✅ Successfully synced {$syncedCount} prices for {$priceDate}");
            return 0;

        } catch (\Exception $e) {
            $this->error('Error syncing prices: ' . $e->getMessage());
            \Log::error('Kalimati Sync Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Parse price string and return numeric value
     */
    private function parsePrice($priceString): float
    {
        if (empty($priceString)) {
            return 0;
        }

        if (is_numeric($priceString)) {
            return (float) $priceString;
        }

        // Remove "Rs." and parse
        $cleaned = str_replace(['Rs.', 'Rs', ',', ' '], '', (string) $priceString);
        return (float) ($cleaned ?: 0);
    }

    /**
     * Map produce names to Nepali translations
     */
    private function getNepaliName($produceName): string
    {
        $translations = [
            'Large tomato (Indian)' => 'ठूलो टमाटर (भारतीय)',
            'Small tomato (local)' => 'सानो टमाटर (स्थानीय)',
            'Potato red' => 'आलु',
            'Carrot (local)' => 'गाजर (स्थानीय)',
            'Cauliflower local' => 'काउली (स्थानीय)',
            'Radish white (local)' => 'मूला (स्थानीय)',
            'Bitter gourd' => 'करेला',
            'Cucumber (local)' => 'खीरा (स्थानीय)',
            'Ginger' => 'अदुवा',
            'Garlic green' => 'लसुन (हरियो)',
            'Green chili (bullet)' => 'हरियो खर्सानी',
            'Dried coriander' => 'धनिया (सुकेको)',
            'Green coriander' => 'हरियो धनिया',
            'Mustard greens' => 'राहो साग',
            'Cabbage' => 'बन्दा',
            'Banana' => 'केरा',
            'Lemon' => 'कागती',
            'Orange (Nepali)' => 'सुन्तला',
            'Grapes (green)' => 'अँगुर (हरियो)',
            'Watermelon (green)' => 'तरबुजा',
            'Strawberry' => 'स्ट्रबेरी',
            'Kiwi' => 'किवी',
            'Mushroom (virgin)' => 'च्याउ',
            'Amla' => 'आँवला',
        ];

        return $translations[$produceName] ?? $produceName;
    }
}
