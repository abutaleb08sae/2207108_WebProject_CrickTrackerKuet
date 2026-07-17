<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BowlingScorecard extends Model
{
    protected $table = 'bowling_scorecards';
    protected $fillable = [
        'innings_id', 'player_id', 'balls_thrown', 
        'maidens', 'runs_conceded', 'wickets_taken', 
        'wides_conceded', 'no_balls_conceded'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getFormattedOversAttribute()
    {
        $overs = floor($this->balls_thrown / 6);
        $balls = $this->balls_thrown % 6;
        return $overs . '.' . $balls;
    }

    public function getEconomyAttribute()
    {
        if ($this->balls_thrown == 0) return 0.00;
        return round(($this->runs_conceded / ($this->balls_thrown / 6)), 2);
    }
}