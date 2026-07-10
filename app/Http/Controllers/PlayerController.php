<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('team')->get();
        return view('admin.players.index', compact('players'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('admin.players.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'role' => 'required|in:Batsman,Bowler,All-rounder,Wicketkeeper',
            'student_id' => 'required|string|unique:players,student_id|max:50',
            'matches_played' => 'required|integer|min:0',
            'total_runs' => 'required|integer|min:0',
            'total_wickets' => 'required|integer|min:0',
        ]);

        Player::create($request->all());

        return redirect()->route('players.index')->with('success', 'Player registered successfully!');
    }

    public function show(Player $player)
    {
    }

    public function edit(Player $player)
    {
        $teams = Team::all();
        return view('admin.players.edit', compact('player', 'teams'));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'role' => 'required|in:Batsman,Bowler,All-rounder,Wicketkeeper',
            'student_id' => 'required|string|max:50|unique:players,student_id,' . $player->id,
            'matches_played' => 'required|integer|min:0',
            'total_runs' => 'required|integer|min:0',
            'total_wickets' => 'required|integer|min:0',
        ]);

        $player->update($request->all());

        return redirect()->route('players.index')->with('success', 'Player profile updated successfully!');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Player removed successfully!');
    }
}