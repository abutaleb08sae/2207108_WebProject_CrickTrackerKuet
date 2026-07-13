<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchScore extends Model
{
    protected $fillable = [
        'fixture_id',
        'runs',
        'wickets',
        'balls_bowled',
        'current_innings',
        'toss_winner_id',
        'toss_decision',
        'innings_one_batting_team_id',
        'innings_two_batting_team_id',
        'innings_one_runs',
        'innings_one_wickets',
        'innings_one_balls',
        'innings_two_runs',
        'innings_two_wickets',
        'innings_two_balls',
        'match_result_string'
    ];

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }
}