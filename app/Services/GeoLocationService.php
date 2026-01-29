<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoLocationService
{
    /**
     * Detect country code from IP address
     * Using ipapi.co (Free tier: 1000 requests/day, no API key needed for basic)
     */
    public function getCountryCodeFromIp(string $ip): ?string
    {
        // Skip for local IPs
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return null; // or return default for testing like 'CL'
        }

        try {
            $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/");

            if ($response->successful()) {
                $data = $response->json();
                return $data['country'] ?? null; // ISO Alpha-2 code (e.g., CL, AR)
            }
        } catch (\Exception $e) {
            Log::warning('GeoLocation detection failed: ' . $e->getMessage());
        }

        return null;
    }
}
