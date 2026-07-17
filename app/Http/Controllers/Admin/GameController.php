<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of scheduled and completed fixtures.
     */
    public function index()
    {
        $games = Game::with(['team1', 'team2', 'winner'])
            ->orderBy('match_date', 'desc')
            ->paginate(10);

        return view('admin.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new match fixture.
     */
    public function create()
    {
        $teams = Team::orderBy('name', 'asc')->get();
        return view('admin.games.create', compact('teams'));
    }

    /**
     * Store a newly scheduled match fixture in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'match_number' => 'required|string|unique:games,match_number',
            'team1_id'     => 'required|exists:teams,id|different:team2_id',
            'team2_id'     => 'required|exists:teams,id',
            'match_date'   => 'required|date',
            'venue'        => 'required|string|max:255',
            'status'       => 'required|in:Scheduled,Live,Completed,Abandoned',
        ], [
            'team1_id.different' => 'A team cannot play against itself.',
        ]);

        Game::create($validated);

        return redirect()->route('games.index')
            ->with('success', 'Match fixture has been scheduled successfully!');
    }

    /**
     * Display detailed scorecards and statistics for a specific match.
     */
    public function show(Game $game)
    {
        $game->load(['team1', 'team2', 'winner', 'playerOfTheMatch']);
        return view('admin.games.show', compact('game'));
    }

    /**
     * Show the form for editing match information, statuses, and score summaries.
     */
    public function edit(Game $game)
    {
        $teams = Team::orderBy('name', 'asc')->get();
        
        // Fetch players belonging only to the two competing teams for Player of the Match selection
        $players = Player::whereIn('team_id', [$game->team1_id, $game->team2_id])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.games.edit', compact('game', 'teams', 'players'));
    }

    /**
     * Update match status, scores, outcomes, and rewards in storage.
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'match_number'           => 'required|string|unique:games,match_number,' . $game->id,
            'team1_id'               => 'required|exists:teams,id|different:team2_id',
            'team2_id'               => 'required|exists:teams,id',
            'match_date'             => 'required|date',
            'venue'                  => 'required|string|max:255',
            'status'                 => 'required|in:Scheduled,Live,Completed,Abandoned',
            'team1_score'            => 'nullable|string|max:100',
            'team2_score'            => 'nullable|string|max:100',
            'winner_id'              => 'nullable|exists:teams,id',
            'player_of_the_match_id' => 'nullable|exists:players,id',
            'result_description'     => 'nullable|string|max:255',
        ], [
            'team1_id.different' => 'A team cannot play against itself.',
        ]);

        // Basic verification to ensure selected winner or MVP belongs to the match context
        if ($request->filled('winner_id') && !in_array($request->winner_id, [$request->team1_id, $request->team2_id])) {
            return back()->withErrors(['winner_id' => 'The winning team must be one of the two competing teams.'])->withInput();
        }

        $game->update($validated);

        return redirect()->route('games.index')
            ->with('success', 'Match record details updated successfully!');
    }

    /**
     * Remove a match fixture from the registry.
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return redirect()->route('games.index')
            ->with('success', 'Match fixture deleted successfully.');
    }
}