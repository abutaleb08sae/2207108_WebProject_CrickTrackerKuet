<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CricketNewsController extends Controller
{
    /**
     * Display the international news skeleton view layout instantly.
     * The actual content is loaded immediately after via an asynchronous AJAX request.
     */
    public function index()
    {
        return view('public.cricket-news');
    }

    /**
     * Asynchronously connects to the external NewsAPI server, executes targeted queries
     * for global cricket matching keywords, and responds with optimized JSON data streams.
     */
    public function getNewsData()
    {
        // 1. Fetch the unique key securely from the environment configurations
        $apiKey = config('services.newsapi.key') ?? env('NEWS_API_KEY');
        
        // 2. Wrap the API transaction layer in a try-catch block to handle communication offline dropouts gracefully
        try {
            // 3. Request fresh articles containing cricket parameters sorted from newest to oldest
            $response = Http::timeout(10)->get("https://newsapi.org/v2/everything", [
                'q'        => 'cricket match OR international cricket',
                'language' => 'en',
                'sortBy'   => 'publishedAt',
                'apiKey'   => $apiKey
            ]);

            // 4. Check if the connection established smoothly with an HTTP 200 condition code
            if ($response->successful()) {
                $data = $response->json();
                $articles = $data['articles'] ?? [];
                
                // Return a clean, fast JSON response payload back to the JavaScript AJAX call
                return response()->json($articles, 200);
            }

            // Fallback tracking context if unauthorized or limits are exhausted
            Log::error("NewsAPI server engine returned an error status code: " . $response->status());
            return response()->json([], $response->status());

        } catch (\Exception $e) {
            // Logs actual physical drops in connectivity (e.g., local Wi-Fi/cURL timeouts)
            Log::error("Failed to connect to backend NewsAPI servers: " . $e->getMessage());
            return response()->json([], 500);
        }
    }
}