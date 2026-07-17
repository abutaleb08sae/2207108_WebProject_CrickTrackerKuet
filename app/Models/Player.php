<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'team_id', 'name', 'photo_path', 'role', 'nationality', 
        'batting_style', 'bowling_style', 'date_of_birth', 
        'height', 'jersey_number', 'debut_date', 'biography'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function careerStats()
    {
        return $this->hasMany(PlayerStat::class);
    }

    public function battingScorecards()
    {
        return $this->hasMany(BattingScorecard::class);
    }

    public function bowlingScorecards()
    {
        return $this->hasMany(BowlingScorecard::class);
    }
}