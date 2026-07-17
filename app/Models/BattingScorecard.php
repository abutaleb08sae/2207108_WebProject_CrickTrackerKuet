<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BattingScorecard extends Model
{
    protected $table = 'batting_scorecards';
    protected $fillable = [
        'innings_id', 'player_id', 'dismissal_description', 
        'bowler_id', 'fielder_id', 'runs', 'balls_faced', 'fours', 'sixes'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function bowler()
    {
        return $this->belongsTo(Player::class, 'bowler_id');
    }

    public function fielder()
    {
        return $this->belongsTo(Player::class, 'fielder_id');
    }

    public function getStrikeRateAttribute()
    {
        if ($this->balls_faced == 0) return 0.00;
        return round(($this->runs / $this->balls_faced) * 100, 2);
    }
}