<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\MatchScore;
use Illuminate\Http\Request;

class ScoringController extends Controller
{
    public function index()
    {
        $liveFixtures = Fixture::where('status', 'LIVE')->with(['teamOne', 'teamTwo'])->get();
        return view('admin.scoring.index', compact('liveFixtures'));
    }

    public function showDashboard($fixtureId)
    {
        $fixture = Fixture::with(['teamOne', 'teamTwo'])->findOrFail($fixtureId);
        
        $score = MatchScore::firstOrCreate(
            ['fixture_id' => $fixtureId],
            [
                'runs' => 0, 
                'wickets' => 0, 
                'balls_bowled' => 0, 
                'current_innings' => 1,
                'innings_one_runs' => 0,
                'innings_one_wickets' => 0,
                'innings_one_balls' => 0,
                'innings_two_runs' => 0,
                'innings_two_wickets' => 0,
                'innings_two_balls' => 0,
                'match_result_string' => null
            ]
        );

        return view('admin.scoring.dashboard', compact('fixture', 'score'));
    }

    public function saveToss(Request $request, $fixtureId)
    {
        $request->validate([
            'toss_winner_id' => 'required|numeric',
            'toss_decision' => 'required|in:BAT,BOWL'
        ]);

        $fixture = Fixture::findOrFail($fixtureId);
        $score = MatchScore::where('fixture_id', $fixtureId)->firstOrFail();

        $tossWinner = $request->toss_winner_id;
        $tossDecision = $request->toss_decision;
        
        $teamOneId = $fixture->team_one_id;
        $teamTwoId = $fixture->team_two_id;

        if ($tossWinner == $teamOneId) {
            $inningsOneBatting = ($tossDecision === 'BAT') ? $teamOneId : $teamTwoId;
        } else {
            $inningsOneBatting = ($tossDecision === 'BAT') ? $teamTwoId : $teamOneId;
        }

        $score->update([
            'toss_winner_id' => $tossWinner,
            'toss_decision' => $tossDecision,
            'innings_one_batting_team_id' => $inningsOneBatting,
            'innings_two_batting_team_id' => ($inningsOneBatting == $teamOneId) ? $teamTwoId : $teamOneId,
            'current_innings' => 1
        ]);

        return redirect()->action([self::class, 'showDashboard'], ['fixture' => $fixtureId])
                         ->with('success', 'Toss recorded! Scoring engine initialized.');
    }

    public function updateScore(Request $request, $fixtureId)
    {
        $fixture = Fixture::findOrFail($fixtureId);
        $score = MatchScore::where('fixture_id', $fixtureId)->firstOrFail();
        $action = $request->input('action');
        $currentInnings = $score->current_innings;

        if ($currentInnings == 1) {
            $runs = (int)($score->innings_one_runs ?? 0);
            $wickets = (int)($score->innings_one_wickets ?? 0);
            $balls = (int)($score->innings_one_balls ?? 0);
        } else {
            $runs = (int)($score->innings_two_runs ?? 0);
            $wickets = (int)($score->innings_two_wickets ?? 0);
            $balls = (int)($score->innings_two_balls ?? 0);
        }

        switch ($action) {
            case 'add_dot':
                $balls++;
                break;
            case 'add_run':
                $runs += 1; $balls++;
                break;
            case 'add_run_2':
                $runs += 2; $balls++;
                break;
            case 'add_run_3':
                $runs += 3; $balls++;
                break;
            case 'add_four':
                $runs += 4; $balls++;
                break;
            case 'add_run_5':
                $runs += 5; $balls++;
                break;
            case 'add_six':
                $runs += 6; $balls++;
                break;
            case 'add_wide':
            case 'add_noball':
                $runs += 1;
                break;
            case 'add_wicket':
                if ($wickets < 10) {
                    $wickets++;
                    $balls++;
                }
                break;
            case 'end_innings':
                if ($currentInnings == 1) {
                    $score->update([
                        'current_innings' => 2,
                        'runs' => 0,
                        'wickets' => 0,
                        'balls_bowled' => 0,
                        'innings_two_runs' => 0,
                        'innings_two_wickets' => 0,
                        'innings_two_balls' => 0
                    ]);
                    return redirect()->back()->with('success', '1st Innings closed! Target set.');
                }
                break;

            case 'end_match':
                $teamOneModel = $fixture->teamOne;
                $teamTwoModel = $fixture->teamTwo;

                $i1Team = ($score->innings_one_batting_team_id == $fixture->team_one_id) ? $teamOneModel : $teamTwoModel;
                $i2Team = ($score->innings_two_batting_team_id == $fixture->team_one_id) ? $teamOneModel : $teamTwoModel;

                $i1Runs = (int)($score->innings_one_runs ?? 0);
                $i2Runs = (int)($score->innings_two_runs ?? 0);
                $i2Wickets = (int)($score->innings_two_wickets ?? 0);

                // FIXED: Explicitly calculate winner_id and update main columns to prevent standings table ties
                if ($i1Runs > $i2Runs) {
                    $winnerId = $score->innings_one_batting_team_id;
                    $resultText = $i1Team->name . " won by " . ($i1Runs - $i2Runs) . " runs";
                } elseif ($i2Runs > $i1Runs) {
                    $winnerId = $score->innings_two_batting_team_id;
                    $resultText = $i2Team->name . " won by " . (10 - $i2Wickets) . " wickets";
                } else {
                    $winnerId = null;
                    $resultText = "Match Tied";
                }

                $score->update([
                    'match_result_string' => $resultText,
                    'innings_two_runs' => $i2Runs,
                    'innings_two_wickets' => $i2Wickets
                ]);

                // Update the fixture status alongside the winner declaration to trigger system standings update recalculations
                $fixture->update([
                    'status' => 'COMPLETED', 
                    'winner_id' => $winnerId
                ]);

                return redirect()->route('scoring.index')->with('success', 'Match scored completely! Leaderboards updated.');

            case 'reset':
                $score->update([
                    'runs' => 0, 'wickets' => 0, 'balls_bowled' => 0, 'current_innings' => 1,
                    'toss_winner_id' => null, 'toss_decision' => null,
                    'innings_one_batting_team_id' => null, 'innings_two_batting_team_id' => null,
                    'innings_one_runs' => 0, 'innings_one_wickets' => 0, 'innings_one_balls' => 0,
                    'innings_two_runs' => 0, 'innings_two_wickets' => 0, 'innings_two_balls' => 0,
                    'match_result_string' => null
                ]);
                return redirect()->back()->with('success', 'Match completely reset.');
        }

        if ($currentInnings == 1) {
            $score->update([
                'innings_one_runs' => $runs, 'innings_one_wickets' => $wickets, 'innings_one_balls' => $balls,
                'runs' => $runs, 'wickets' => $wickets, 'balls_bowled' => $balls
            ]);
        } else {
            $score->update([
                'innings_two_runs' => $runs, 'innings_two_wickets' => $wickets, 'innings_two_balls' => $balls,
                'runs' => $runs, 'wickets' => $wickets, 'balls_bowled' => $balls
            ]);
        }

        return redirect()->back();
    }
}