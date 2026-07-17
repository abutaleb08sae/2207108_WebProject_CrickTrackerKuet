<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerController extends Controller
{
    /**
     * Display a listing of the players.
     */
    public function index()
    {
        $players = Player::with('team')->orderBy('name', 'asc')->get();
        return view('admin.players.index', compact('players'));
    }

    /**
     * Show the form for creating a new player.
     */
    public function create()
    {
        $teams = Team::orderBy('name', 'asc')->get();
        return view('admin.players.create', compact('teams'));
    }

    /**
     * Store a newly created player in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'role' => 'required|in:Batsman,Bowler,All-rounder,Wicketkeeper',
            'student_id' => 'required|string|unique:players,student_id|max:50',
            'jersey_number' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'batting_style' => 'nullable|string|max:50',
            'bowling_style' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'height' => 'nullable|string|max:20',
            'debut_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Stat Tracking Validations
            'matches_played' => 'required|integer|min:0',
            'total_runs' => 'required|integer|min:0',
            'total_wickets' => 'nullable|integer|min:0', 
            'wickets_taken' => 'nullable|integer|min:0',
            'highest_score' => 'nullable|integer|min:0',
            'batting_average' => 'nullable|numeric|min:0|max:999.99',
            'batting_strike_rate' => 'nullable|numeric|min:0|max:999.99',
            'fifties' => 'nullable|integer|min:0',
            'hundreds' => 'nullable|integer|min:0',
            'best_bowling_figures' => 'nullable|string|max:20',
            'bowling_economy' => 'nullable|numeric|min:0|max:99.99',
            'bowling_average' => 'nullable|numeric|min:0|max:999.99',
            'five_wicket_hauls' => 'nullable|integer|min:0',
            'catches' => 'nullable|integer|min:0',
            'stumpings' => 'nullable|integer|min:0',
        ]);

        // Map and extract birth date field securely
        if (isset($validated['date_of_birth'])) {
            $validated['birth_date'] = $validated['date_of_birth'];
            unset($validated['date_of_birth']);
        }

        // Standardize bowling statistics array keys explicitly
        if (isset($validated['wickets_taken'])) {
            $validated['wickets_taken'] = $validated['wickets_taken'];
            unset($validated['total_wickets']);
        } elseif (isset($validated['total_wickets'])) {
            $validated['wickets_taken'] = $validated['total_wickets'];
            unset($validated['total_wickets']);
        }

        // Handle profile photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('player_photos', 'public');
            $validated['photo_path'] = $path;
            $validated['image_path'] = $path;
        }

        Player::create($validated);

        return redirect()->route('players.index')->with('success', 'Player registered successfully!');
    }

    /**
     * Display the specified player's detailed profile card.
     */
    public function show(Player $player)
    {
        return view('admin.players.show', compact('player'));
    }

    /**
     * Show the form for editing the specified player.
     */
    public function edit(Player $player)
    {
        $teams = Team::orderBy('name', 'asc')->get();
        return view('admin.players.edit', compact('player', 'teams'));
    }

    /**
     * Update the specified player in storage.
     */
    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'role' => 'required|in:Batsman,Bowler,All-rounder,Wicketkeeper',
            'student_id' => 'required|string|max:50|unique:players,student_id,' . $player->id,
            'jersey_number' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'batting_style' => 'nullable|string|max:50',
            'bowling_style' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'height' => 'nullable|string|max:20',
            'debut_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Stat Tracking Validations
            'matches_played' => 'required|integer|min:0',
            'total_runs' => 'required|integer|min:0',
            'total_wickets' => 'nullable|integer|min:0',
            'wickets_taken' => 'nullable|integer|min:0',
            'highest_score' => 'nullable|integer|min:0',
            'batting_average' => 'nullable|numeric|min:0|max:999.99',
            'batting_strike_rate' => 'nullable|numeric|min:0|max:999.99',
            'fifties' => 'nullable|integer|min:0',
            'hundreds' => 'nullable|integer|min:0',
            'best_bowling_figures' => 'nullable|string|max:20',
            'bowling_economy' => 'nullable|numeric|min:0|max:99.99',
            'bowling_average' => 'nullable|numeric|min:0|max:999.99',
            'five_wicket_hauls' => 'nullable|integer|min:0',
            'catches' => 'nullable|integer|min:0',
            'stumpings' => 'nullable|integer|min:0',
        ]);

        // Map and extract birth date field securely
        if (isset($validated['date_of_birth'])) {
            $validated['birth_date'] = $validated['date_of_birth'];
            unset($validated['date_of_birth']);
        }

        // Standardize bowling statistics array keys explicitly
        if (isset($validated['wickets_taken'])) {
            $validated['wickets_taken'] = $validated['wickets_taken'];
            unset($validated['total_wickets']);
        } elseif (isset($validated['total_wickets'])) {
            $validated['wickets_taken'] = $validated['total_wickets'];
            unset($validated['total_wickets']);
        }

        // Handle profile photo modification
        if ($request->hasFile('photo')) {
            if ($player->photo_path) {
                Storage::disk('public')->delete($player->photo_path);
            }
            if ($player->image_path && $player->image_path !== $player->photo_path) {
                Storage::disk('public')->delete($player->image_path);
            }

            $path = $request->file('photo')->store('player_photos', 'public');
            $validated['photo_path'] = $path;
            $validated['image_path'] = $path;
        }

        $player->update($validated);

        return redirect()->route('players.index')->with('success', 'Player profile updated successfully!');
    }

    /**
     * Remove the specified player from storage.
     */
    public function destroy(Player $player)
    {
        if ($player->photo_path) {
            Storage::disk('public')->delete($player->photo_path);
        }
        if ($player->image_path && $player->image_path !== $player->photo_path) {
            Storage::disk('public')->delete($player->image_path);
        }

        $player->delete();

        return redirect()->route('players.index')->with('success', 'Player removed successfully!');
    }
}