<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    public function index()
    {
        $fixtures = Fixture::with(['teamOne', 'teamTwo'])->orderBy('match_datetime', 'asc')->get();
        return view('admin.fixtures.index', compact('fixtures'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('admin.fixtures.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_one_id' => 'required|exists:teams,id|different:team_two_id',
            'team_two_id' => 'required|exists:teams,id',
            'match_datetime' => 'required|date|after:now',
            'venue' => 'required|string|max:100',
            'tournament_type' => 'required|string|max:150', // Added tracking validation rule
            'status' => 'required|in:Upcoming,Live,Completed',
        ]);

        // Automatically captures and saves team_one_id, team_two_id, venue, tournament_type, status, and match_datetime safely
        Fixture::create($request->all());

        return redirect()->route('fixtures.index')->with('success', 'Match scheduled successfully!');
    }

    public function show(Fixture $fixture)
    {
    }

    public function edit(Fixture $fixture)
    {
        $teams = Team::all();
        return view('admin.fixtures.edit', compact('fixture', 'teams'));
    }

    public function update(Request $request, Fixture $fixture)
    {
        $request->validate([
            'team_one_id' => 'required|exists:teams,id|different:team_two_id',
            'team_two_id' => 'required|exists:teams,id',
            'match_datetime' => 'required|date',
            'venue' => 'required|string|max:100',
            'tournament_type' => 'required|string|max:150', // Added tracking validation rule
            'status' => 'required|in:Upcoming,Live,Completed',
        ]);

        $fixture->update($request->all());

        return redirect()->route('fixtures.index')->with('success', 'Fixture updated successfully!');
    }

    public function destroy(Fixture $fixture)
    {
        $fixture->delete();
        return redirect()->route('fixtures.index')->with('success', 'Fixture removed successfully!');
    }
}