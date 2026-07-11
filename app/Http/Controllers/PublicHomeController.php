<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Http\Request;

class PublicHomeController extends Controller
{
    public function index()
    {
        $liveMatches = Fixture::where('status', 'Live')
            ->with(['teamOne', 'teamTwo', 'matchScore'])
            ->get();

        $upcomingMatches = Fixture::where('status', 'Upcoming')
            ->with(['teamOne', 'teamTwo'])
            ->orderBy('match_datetime', 'asc')
            ->take(5)
            ->get();

        $completedMatches = Fixture::where('status', 'Completed')
            ->with(['teamOne', 'teamTwo', 'matchScore'])
            ->orderBy('match_datetime', 'desc')
            ->take(5)
            ->get();

        return view('public.home', compact('liveMatches', 'upcomingMatches', 'completedMatches'));
    }
}