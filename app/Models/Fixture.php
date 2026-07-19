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
        'tournament_type', // <-- Added to allow mass assignment from your form inputs
        'status', 
        'winner_id', // <-- Added to allow saving the winning team on completion
        'player_of_the_match_id', // <-- Added to store the MVP award reference safely
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

    public function liveState()
    {
        return $this->hasOne(LiveMatchState::class, 'fixture_id');
    }

    public function battingScorecards()
    {
        return $this->hasMany(BattingScorecard::class, 'fixture_id');
    }

    public function bowlingScorecards()
    {
        return $this->hasMany(BowlingScorecard::class, 'fixture_id');
    }

    /**
     * Relationship to fetch ball-by-ball commentary streams ordered newest first.
     */
    public function commentaries()
    {
        return $this->hasMany(CommentaryLog::class, 'fixture_id')->orderBy('id', 'desc');
    }

    /**
     * Relationship mapping to the specific player matching the match award tracking row.
     */
    public function playerOfTheMatch()
    {
        return $this->belongsTo(Player::class, 'player_of_the_match_id');
    }
}