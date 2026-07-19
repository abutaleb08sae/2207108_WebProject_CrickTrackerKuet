<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\MatchScore;
use App\Models\Player;
use App\Models\LiveMatchState;
use App\Models\BattingScorecard;
use App\Models\BowlingScorecard;
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
                'target_runs' => null,
                'match_result_string' => null
            ]
        );

        $state = LiveMatchState::firstOrCreate(['fixture_id' => $fixtureId]);
        
        $team1Players = Player::where('team_id', $fixture->team_one_id)->get();
        $team2Players = Player::where('team_id', $fixture->team_two_id)->get();

        return view('admin.scoring.dashboard', compact('fixture', 'score', 'state', 'team1Players', 'team2Players'));
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

        $score->toss_winner_id = $tossWinner;
        $score->toss_decision = $tossDecision;
        $score->innings_one_batting_team_id = $inningsOneBatting;
        $score->innings_two_batting_team_id = ($inningsOneBatting == $teamOneId) ? $teamTwoId : $teamOneId;
        $score->current_innings = 1;
        $score->runs = 0;
        $score->wickets = 0;
        $score->balls_bowled = 0;
        $score->save();

        return redirect()->action([self::class, 'showDashboard'], ['fixture' => $fixtureId])
                         ->with('success', 'Toss recorded! Scoring engine initialized.');
    }

    public function updateActivePlayers(Request $request, $fixtureId)
    {
        $state = LiveMatchState::where('fixture_id', $fixtureId)->firstOrFail();
        $state->update($request->only(['batsman_on_strike_id', 'batsman_off_strike_id', 'current_bowler_id']));

        return back()->with('success', 'On-field player configuration updated.');
    }

    public function updateScore(Request $request, $fixtureId)
    {
        $fixture = Fixture::findOrFail($fixtureId);
        $score = MatchScore::where('fixture_id', $fixtureId)->firstOrFail();
        $state = LiveMatchState::where('fixture_id', $fixtureId)->firstOrFail();
        
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

        $strikerCard = null;
        $bowlerCard = null;

        if (!in_array($action, ['end_innings', 'end_match', 'reset']) && $state->batsman_on_strike_id && $state->current_bowler_id) {
            $battingTeamId = ($currentInnings == 1) ? $score->innings_one_batting_team_id : $score->innings_two_batting_team_id;
            $bowlingTeamId = ($battingTeamId == $fixture->team_one_id) ? $fixture->team_two_id : $fixture->team_one_id;

            $strikerCard = BattingScorecard::firstOrCreate([
                'fixture_id' => $fixtureId, 'player_id' => $state->batsman_on_strike_id, 'team_id' => $battingTeamId
            ]);
            $bowlerCard = BowlingScorecard::firstOrCreate([
                'fixture_id' => $fixtureId, 'player_id' => $state->current_bowler_id, 'team_id' => $bowlingTeamId
            ]);
        }

        switch ($action) {
            case 'add_dot':
                $balls++;
                if ($strikerCard) $strikerCard->increment('balls_faced');
                if ($bowlerCard) $bowlerCard->increment('balls_bowled');
                break;
            case 'add_run':
                $runs += 1; $balls++;
                if ($strikerCard) { $strikerCard->increment('runs_scored', 1); $strikerCard->increment('balls_faced'); }
                if ($bowlerCard) { $bowlerCard->increment('runs_conceded', 1); $bowlerCard->increment('balls_bowled'); }
                $this->rotateStrike($state);
                break;
            case 'add_run_2':
                $runs += 2; $balls++;
                if ($strikerCard) { $strikerCard->increment('runs_scored', 2); $strikerCard->increment('balls_faced'); }
                if ($bowlerCard) { $bowlerCard->increment('runs_conceded', 2); $bowlerCard->increment('balls_bowled'); }
                break;
            case 'add_run_3':
                $runs += 3; $balls++;
                if ($strikerCard) { $strikerCard->increment('runs_scored', 3); $strikerCard->increment('balls_faced'); }
                if ($bowlerCard) { $bowlerCard->increment('runs_conceded', 3); $bowlerCard->increment('balls_bowled'); }
                $this->rotateStrike($state);
                break;
            case 'add_four':
                $runs += 4; $balls++;
                if ($strikerCard) { $strikerCard->increment('runs_scored', 4); $strikerCard->increment('fours_hit'); $strikerCard->increment('balls_faced'); }
                if ($bowlerCard) { $bowlerCard->increment('runs_conceded', 4); $bowlerCard->increment('balls_bowled'); }
                break;
            case 'add_run_5':
                $runs += 5; $balls++;
                if ($strikerCard) { $strikerCard->increment('runs_scored', 5); $strikerCard->increment('balls_faced'); }
                if ($bowlerCard) { $bowlerCard->increment('runs_conceded', 5); $bowlerCard->increment('balls_bowled'); }
                $this->rotateStrike($state);
                break;
            case 'add_six':
                $runs += 6; $balls++;
                if ($strikerCard) { $strikerCard->increment('runs_scored', 6); $strikerCard->increment('sixes_hit'); $strikerCard->increment('balls_faced'); }
                if ($bowlerCard) { $bowlerCard->increment('runs_conceded', 6); $bowlerCard->increment('balls_bowled'); }
                break;
            case 'add_wide':
            case 'add_noball':
                $runs += 1;
                if ($bowlerCard) $bowlerCard->increment('runs_conceded', 1);
                break;
            case 'add_wicket':
                if ($wickets < 10) {
                    $wickets++;
                    $balls++;
                    
                    $bowlerName = Player::find($state->current_bowler_id)?->name ?? 'Bowler';

                    if ($strikerCard) {
                        $strikerCard->increment('balls_faced');
                        $strikerCard->update([
                            'out_status' => 'b ' . $bowlerName,
                            'dismissal_description' => 'b ' . $bowlerName
                        ]);
                    }
                    if ($bowlerCard) {
                        $bowlerCard->increment('balls_bowled');
                        $bowlerCard->increment('wickets_taken');
                    }
                }
                break;
                
            case 'end_innings':
                if ($currentInnings == 1) {
                    $score->innings_one_runs = $runs;
                    $score->innings_one_wickets = $wickets;
                    $score->innings_one_balls = $balls;
                    $score->target_runs = $runs + 1;

                    $score->current_innings = 2;
                    $score->runs = 0;
                    $score->wickets = 0;
                    $score->balls_bowled = 0;
                    $score->innings_two_runs = 0;
                    $score->innings_two_wickets = 0;
                    $score->innings_two_balls = 0;
                    $score->save();

                    $state->update(['batsman_on_strike_id' => null, 'batsman_off_strike_id' => null, 'current_bowler_id' => null]);

                    return redirect()->back()->with('success', '1st Innings closed! Target set to ' . $score->target_runs . ' runs.');
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

                $score->match_result_string = $resultText;
                $score->innings_two_runs = $i2Runs;
                $score->innings_two_wickets = $i2Wickets;
                $score->save();

                $fixture->status = 'COMPLETED';
                $fixture->winner_id = $winnerId;
                $fixture->save();

                return redirect()->route('public.home')->with('success', 'Match scored completely! Leaderboards updated.');

            case 'reset':
                BattingScorecard::where('fixture_id', $fixtureId)->delete();
                BowlingScorecard::where('fixture_id', $fixtureId)->delete();
                $state->update(['batsman_on_strike_id' => null, 'batsman_off_strike_id' => null, 'current_bowler_id' => null]);

                $score->runs = 0;
                $score->wickets = 0;
                $score->balls_bowled = 0;
                $score->current_innings = 1;
                $score->toss_winner_id = null;
                $score->toss_decision = null;
                $score->innings_one_batting_team_id = null;
                $score->innings_two_batting_team_id = null;
                $score->innings_one_runs = 0;
                $score->innings_one_wickets = 0;
                $score->innings_one_balls = 0;
                $score->innings_two_runs = 0;
                $score->innings_two_wickets = 0;
                $score->innings_two_balls = 0;
                $score->target_runs = null;
                $score->match_result_string = null;
                $score->save();
                return redirect()->back()->with('success', 'Match completely reset.');
        }

        if (!in_array($action, ['add_wide', 'add_noball', 'end_innings', 'end_match', 'reset'])) {
            if ($balls > 0 && $balls % 6 === 0) {
                $this->rotateStrike($state);
            }
        }

        if ($bowlerCard) {
            $overFloat = floor($bowlerCard->balls_bowled / 6) + (($bowlerCard->balls_bowled % 6) / 10);
            $bowlerCard->update(['overs_bowled' => $overFloat]);
        }

        // FIXED: Explicitly sync BOTH the innings matrices and global master tracking columns 
        if ($currentInnings == 1) {
            $score->innings_one_runs = $runs;
            $score->innings_one_wickets = $wickets;
            $score->innings_one_balls = $balls;
        } else {
            $score->innings_two_runs = $runs;
            $score->innings_two_wickets = $wickets;
            $score->innings_two_balls = $balls;
        }

        // Synchronize primary operational counters for real-time dashboards
        $score->runs = $runs;
        $score->wickets = $wickets;
        $score->balls_bowled = $balls;
        $score->save();

        return redirect()->back();
    }

    private function rotateStrike($state)
    {
        if ($state->batsman_on_strike_id && $state->batsman_off_strike_id) {
            $temp = $state->batsman_on_strike_id;
            $state->batsman_on_strike_id = $state->batsman_off_strike_id;
            $state->batsman_off_strike_id = $temp;
            $state->save();
        }
    }
}