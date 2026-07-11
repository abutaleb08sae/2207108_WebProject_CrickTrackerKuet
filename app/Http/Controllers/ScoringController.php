<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\MatchScore;
use Illuminate\Http\Request;

class ScoringController extends Controller
{
    public function index()
    {
        $liveFixtures = Fixture::where('status', 'Live')->with(['teamOne', 'teamTwo'])->get();
        return view('admin.scoring.index', compact('liveFixtures'));
    }

    public function showDashboard($fixtureId)
    {
        $fixture = Fixture::with(['teamOne', 'teamTwo'])->findOrFail($fixtureId);
        
        $score = MatchScore::firstOrCreate(
            ['fixture_id' => $fixtureId],
            ['runs' => 0, 'wickets' => 0, 'balls_bowled' => 0, 'current_innings' => 'Innings 1']
        );

        return view('admin.scoring.dashboard', compact('fixture', 'score'));
    }

    public function updateScore(Request $request, $fixtureId)
    {
        $score = MatchScore::where('fixture_id', $fixtureId)->firstOrFail();
        $action = $request->input('action');

        switch ($action) {
            case 'add_run':
                $score->increment('runs', 1);
                $score->increment('balls_bowled', 1);
                break;
            case 'add_four':
                $score->increment('runs', 4);
                $score->increment('balls_bowled', 1);
                break;
            case 'add_six':
                $score->increment('runs', 6);
                $score->increment('balls_bowled', 1);
                break;
            case 'add_dot':
                $score->increment('balls_bowled', 1);
                break;
            case 'add_wicket':
                if ($score->wickets < 10) {
                    $score->increment('wickets', 1);
                    $score->increment('balls_bowled', 1);
                }
                break;
            case 'add_wide':
            case 'add_noball':
                $score->increment('runs', 1);
                break;
            case 'reset':
                $score->update(['runs' => 0, 'wickets' => 0, 'balls_bowled' => 0]);
                break;
        }

        return redirect()->back()->with('success', 'Score updated successfully!');
    }
}