<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CricketNewsController extends Controller
{
    public function index()
    {
        // 1. Pull the unique key securely from the environment file (.env)
        $apiKey = config('services.newsapi.key') ?? env('NEWS_API_KEY');
        
        // 2. Wrap the external call in a try-catch block to handle absolute internet timeouts or offline states
        try {
            // 3. Fire a secure HTTP GET request with dedicated query constraints
            $response = Http::timeout(10)->get("https://newsapi.org/v2/everything", [
                'q'        => 'cricket match OR international cricket',
                'language' => 'en',
                'sortBy'   => 'publishedAt',
                'apiKey'   => $apiKey
            ]);

            // 4. Inspect if the API responded with a 200 OK status condition
            if ($response->successful()) {
                $data = $response->json();
                
                // Extract articles, or fall back to an empty array if empty
                $articles = $data['articles'] ?? [];
                
                return view('public.cricket-news', compact('articles'));
            }

            // If API gives an error code (like 401 Unauthorized or 429 Too Many Requests)
            Log::error("NewsAPI returned an error code: " . $response->status());
            $articles = [];

        } catch (\Exception $e) {
            // Catch complete drops in connection (DNS Failure, cURL timeout, etc.)
            Log::error("Failed to connect to NewsAPI server: " . $e->getMessage());
            $articles = [];
        }

        // Return the layout view cleanly; if errors happened, $articles is empty, firing the fallback UI card
        return view('public.cricket-news', compact('articles'));
    }
}