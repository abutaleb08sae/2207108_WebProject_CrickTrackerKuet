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
        $this->baseHost = env('RAPID_API_HOST');
    }

    /**
     * Fetch match lists categorized by type (defaults to international)
     */
    public function getMatchesList($type = 'international')
    {
        try {
            $response = Http::withHeaders([
                'x-rapidapi-key'  => $this->apiKey,
                'x-rapidapi-host' => $this->baseHost
            ])->get("https://{$this->baseHost}/matches/list", [
                'type' => $type
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error("Cricbuzz API List Error: " . $response->body());
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
            $response = Http::withHeaders([
                'x-rapidapi-key'  => $this->apiKey,
                'x-rapidapi-host' => $this->baseHost
            ])->get("https://{$this->baseHost}/matches/detail", [
                'matchId' => $matchId
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            Log::error("Cricbuzz Detail Exception: " . $e->getMessage());
            return null;
        }
    }
}