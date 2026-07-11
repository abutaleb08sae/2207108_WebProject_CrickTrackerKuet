<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fixture;
use App\Models\News;
use App\Models\Team;

class PublicHomeController extends Controller
{
    public function index()
    {
        $liveMatches = Fixture::with(['teamOne', 'teamTwo', 'matchScore'])
            ->where('status', 'LIVE')
            ->get();

        return view('public.home', compact('liveMatches'));
    }

    public function standings()
    {
        $teams = Team::all();
        $standings = [];

        foreach ($teams as $team) {
            $standings[$team->id] = [
                'name' => $team->name,
                'slug' => $team->slug,
                'played' => 0,
                'won' => 0,
                'lost' => 0,
                'tied' => 0,
                'points' => 0
            ];
        }

        $allFinished = Fixture::where('status', 'COMPLETED')->get();

        foreach ($allFinished as $match) {
            if (!isset($standings[$match->team_one_id]) || !isset($standings[$match->team_two_id])) {
                continue;
            }

            $standings[$match->team_one_id]['played']++;
            $standings[$match->team_two_id]['played']++;

            if ($match->winner_id == $match->team_one_id) {
                $standings[$match->team_one_id]['won']++;
                $standings[$match->team_one_id]['points'] += 2;
                $standings[$match->team_two_id]['lost']++;
            } elseif ($match->winner_id == $match->team_two_id) {
                $standings[$match->team_two_id]['won']++;
                $standings[$match->team_two_id]['points'] += 2;
                $standings[$match->team_one_id]['lost']++;
            } else {
                $standings[$match->team_one_id]['tied']++;
                $standings[$match->team_two_id]['tied']++;
                $standings[$match->team_one_id]['points'] += 1;
                $standings[$match->team_two_id]['points'] += 1;
            }
        }

        uasort($standings, function ($a, $b) {
            if ($b['points'] === $a['points']) {
                return $b['won'] <=> $a['won'];
            }
            return $b['points'] <=> $a['points'];
        });

        return view('public.standings', compact('standings'));
    }

    public function fixtures()
    {
        $upcomingMatches = Fixture::with(['teamOne', 'teamTwo'])
            ->where('status', 'UPCOMING')
            ->orderBy('match_datetime', 'asc')
            ->get();

        return view('public.fixtures', compact('upcomingMatches'));
    }

    public function results()
    {
        $completedMatches = Fixture::with(['teamOne', 'teamTwo', 'matchScore'])
            ->where('status', 'COMPLETED')
            ->orderBy('match_datetime', 'desc')
            ->get();

        return view('public.results', compact('completedMatches'));
    }

    public function newsArchive()
    {
        $allNews = News::orderBy('created_at', 'desc')->paginate(10);
        return view('public.news_index', compact('allNews'));
    }
}