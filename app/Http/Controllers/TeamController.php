<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('captain')->orderBy('ranking', 'asc')->get();
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        $players = Player::all();
        return view('admin.teams.create', compact('players'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:teams,name|max:100',
            'short_name' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'captain_id' => 'nullable|exists:players,id',
            'coach' => 'nullable|string|max:255',
            'club_history' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'ranking' => 'nullable|integer',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'home_ground' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('team_logos', 'public');
            $validated['logo_path'] = $path;
        }

        Team::create($validated);

        return redirect()->route('teams.index')->with('success', 'Team created successfully!');
    }

    public function show(Team $team)
    {
        // Not required for basic team operational views
    }

    public function edit(Team $team)
    {
        $players = Player::where('team_id', $team->id)->get();
        return view('admin.teams.edit', compact('team', 'players'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:teams,name,' . $team->id,
            'short_name' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'captain_id' => 'nullable|exists:players,id',
            'coach' => 'nullable|string|max:255',
            'club_history' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'ranking' => 'nullable|integer',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'home_ground' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            if ($team->logo_path) {
                Storage::disk('public')->delete($team->logo_path);
            }
            $path = $request->file('logo')->store('team_logos', 'public');
            $validated['logo_path'] = $path;
        }

        $team->update($validated);

        return redirect()->route('teams.index')->with('success', 'Team updated successfully!');
    }

    public function destroy(Team $team)
    {
        if ($team->logo_path) {
            Storage::disk('public')->delete($team->logo_path);
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully!');
    }
}