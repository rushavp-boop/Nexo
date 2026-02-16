<?php

require 'vendor/autoload.php';

$http = new \GuzzleHttp\Client();

$urls = [
    'https://kalimatimarket.gov.np/api/price',
    'https://kalimatimarket.gov.np/price',
];

foreach ($urls as $url) {
    echo "\n\n=== Testing: $url ===\n";
    try {
        $response = $http->get($url, [
            'verify' => false,
            'timeout' => 15,
        ]);

        echo "Status: " . $response->getStatusCode() . "\n";
        $body = (string) $response->getBody();
        echo "Body length: " . strlen($body) . "\n";
        echo "First 500 chars: " . substr($body, 0, 500) . "\n";

        $data = json_decode($body, true);
        if (is_array($data)) {
            echo "Parsed as Array! Items: " . count($data) . "\n";
            if (!empty($data)) {
                echo "First item: " . json_encode($data[0], JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "Type: " . gettype($data) . "\n";
            if (is_object($data)) {
                echo "Object keys: " . json_encode(array_keys((array)$data)) . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

