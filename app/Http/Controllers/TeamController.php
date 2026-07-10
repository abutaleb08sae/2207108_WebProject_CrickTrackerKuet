<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:teams,name|max:100',
            'club_history' => 'nullable|string|max:1000',
        ]);

        Team::create([
            'name' => $request->name,
            'club_history' => $request->club_history,
        ]);

        return redirect()->route('teams.index')->with('success', 'Team created successfully!');
    }

    public function show(Team $team)
    {
        // Not required for basic team operational views
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:teams,name,' . $team->id,
            'club_history' => 'nullable|string|max:1000',
        ]);

        $team->update([
            'name' => $request->name,
            'club_history' => $request->club_history,
        ]);

        return redirect()->route('teams.index')->with('success', 'Team updated successfully!');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully!');
    }
}