<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_one_id', 
        'team_two_id', 
        'match_datetime', 
        'venue', 
        'status', 
        'toss_winner_id', 
        'toss_decision', 
        'target_runs', 
        'current_striker_id', 
        'current_non_striker_id', 
        'current_bowler_id'
    ];

    public function teamOne()
    {
        return $this->belongsTo(Team::class, 'team_one_id');
    }

    public function teamTwo()
    {
        return $this->belongsTo(Team::class, 'team_two_id');
    }
    
    public function matchScore()
    {
        return $this->hasOne(MatchScore::class);
    }

    public function innings()
    {
        return $this->hasMany(Innings::class)->orderBy('innings_number', 'asc');
    }

    public function squads()
    {
        return $this->hasMany(MatchSquad::class);
    }

    public function currentStriker()
    {
        return $this->belongsTo(Player::class, 'current_striker_id');
    }

    public function currentNonStriker()
    {
        return $this->belongsTo(Player::class, 'current_non_striker_id');
    }

    public function currentBowler()
    {
        return $this->belongsTo(Player::class, 'current_bowler_id');
    }
}