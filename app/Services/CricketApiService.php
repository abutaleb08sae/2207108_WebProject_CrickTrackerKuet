<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CricketApiService
{
    protected $apiKey;
    protected $baseHost;

    public function __construct()
    {
        $this->apiKey = env('RAPID_API_KEY');
        $this->baseHost = env('RAPID_API_HOST') ?: 'cricbuzz-cricket.p.rapidapi.com';
    }

    /**
     * Fetch match lists categorized by type (defaults to international)
     */
    public function getMatchesList($type = 'international')
    {
        try {
            // Updated to the proper structural Cricbuzz API list path prefix
            $response = Http::withHeaders([
                'x-rapidapi-key'  => $this->apiKey,
                'x-rapidapi-host' => $this->baseHost
            ])->get("https://{$this->baseHost}/matches/v1/list");

            if ($response->successful()) {
                $data = $response->json();
                
                // If filtering by type is explicitly requested (e.g. international, domestic, league)
                if ($type && isset($data['typeMatches'])) {
                    $filteredMatches = [];
                    foreach ($data['typeMatches'] as $matchTypeSlot) {
                        if (strtolower($matchTypeSlot['matchType'] ?? '') === strtolower($type)) {
                            $filteredMatches['typeMatches'][] = $matchTypeSlot;
                        }
                    }
                    return !empty($filteredMatches) ? $filteredMatches : $data;
                }

                return $data;
            }
            
            Log::error("Cricbuzz API List Error: " . $response->status() . " - " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Cricbuzz API List Exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch detailed score information for a single match
     */
    public function getMatchDetails($matchId)
    {
        try {
            // Cricbuzz organizes real-time single scores inside the Match Center endpoint hierarchy
            $response = Http::withHeaders([
                'x-rapidapi-key'  => $this->apiKey,
                'x-rapidapi-host' => $this->baseHost
            ])->get("https://{$this->baseHost}/mcenter/v1/{$matchId}");

            if ($response->successful()) {
                return $response->json();
            }

            // Fallback try for alternate RapidAPI Cricbuzz vendor routes if primary mcenter drops
            $fallbackResponse = Http::withHeaders([
                'x-rapidapi-key'  => $this->apiKey,
                'x-rapidapi-host' => $this->baseHost
            ])->get("https://{$this->baseHost}/matches/v1/detail/{$matchId}");

            if ($fallbackResponse->successful()) {
                return $fallbackResponse->json();
            }

            Log::error("Cricbuzz Detail Error: Status " . $response->status());
            return null;
        } catch (\Exception $e) {
            Log::error("Cricbuzz Detail Exception: " . $e->getMessage());
            return null;
        }
    }
}