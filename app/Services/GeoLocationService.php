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
        // 1. En local, si es IP local, simular una IP real (ej. Chile) para probar
        if (app()->isLocal() && ($ip === '127.0.0.1' || $ip === '::1')) {
            Log::info('GeoLocation: Localhost detected, simulating Santiago IP.');
            $ip = '190.160.0.1'; // IP de ejemplo de Chile
        }

        // 2. Skip for private IPs (10.x, 192.168.x) to avoid API errors
        // (Only skip if we haven't just forced a public IP above)
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '10.') || str_starts_with($ip, '192.168.')) {
            Log::info("GeoLocation: IP {$ip} skipped (local/private).");

            return null;
        }

        try {
            // Log for debugging
            Log::info("GeoLocation: Querying ipapi.co for {$ip}");

            $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/");

            if ($response->successful()) {
                $data = $response->json();
                $country = $data['country'] ?? null;
                Log::info("GeoLocation: Detected {$country} for {$ip} via ipapi.co");

                return $country;
            }

            Log::warning('GeoLocation: ipapi.co error for '.$ip.': '.$response->status().'. Trying fallback...');
        } catch (\Exception $e) {
            Log::error('GeoLocation: ipapi.co failed: '.$e->getMessage());
        }

        // Fallback: ipwhois.app (Free, no key required)
        try {
            Log::info("GeoLocation: Querying fallback ipwhois.app for {$ip}");
            $response = Http::timeout(3)->get("http://ipwhois.app/json/{$ip}");

            if ($response->successful()) {
                $data = $response->json();
                $country = $data['country_code'] ?? null; // Different key: country_code
                Log::info("GeoLocation: Detected {$country} for {$ip} via ipwhois.app");

                return $country;
            }
        } catch (\Exception $e) {
            Log::error('GeoLocation: Fallback failed: '.$e->getMessage());
        }

        return null;
    }
}
