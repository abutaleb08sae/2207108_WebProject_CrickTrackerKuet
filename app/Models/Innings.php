<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Innings extends Model
{
    protected $fillable = [
        'fixture_id', 
        'batting_team_id', 
        'bowling_team_id', 
        'innings_number', 
        'total_runs', 
        'total_wickets', 
        'overs_bowled_balls', 
        'bye_extras', 
        'leg_bye_extras', 
        'wide_extras', 
        'no_ball_extras', 
        'is_declared', 
        'is_completed'
    ];

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    public function battingTeam()
    {
        return $this->belongsTo(Team::class, 'batting_team_id');
    }

    public function bowlingTeam()
    {
        return $this->belongsTo(Team::class, 'bowling_team_id');
    }

    public function battingScorecards()
    {
        return $this->hasMany(BattingScorecard::class)->orderBy('id', 'asc');
    }

    public function bowlingScorecards()
    {
        return $this->hasMany(BowlingScorecard::class)->orderBy('id', 'asc');
    }

    public function balls()
    {
        return $this->hasMany(BallByBallLog::class)->orderBy('over_number', 'desc')->orderBy('ball_number', 'desc');
    }

    /**
     * Accessor to convert raw legal/illegal balls into cricket over notation (e.g., 124 balls -> 20.4 overs)
     */
    public function getFormattedOversAttribute()
    {
        $overs = floor($this->overs_bowled_balls / 6);
        $balls = $this->overs_bowled_balls % 6;
        return $overs . '.' . $balls;
    }
}