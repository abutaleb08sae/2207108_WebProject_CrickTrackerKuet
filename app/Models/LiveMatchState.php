<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveMatchState extends Model
{
    protected $fillable = [
        'fixture_id', 'batsman_on_strike_id', 'batsman_off_strike_id', 
        'current_bowler_id', 'current_over_balls_count', 'this_over_runslog'
    ];

    public function striker() { return $this->belongsTo(Player::class, 'batsman_on_strike_id'); }
    public function nonStriker() { return $this->belongsTo(Player::class, 'batsman_off_strike_id'); }
    public function bowler() { return $this->belongsTo(Player::class, 'current_bowler_id'); }
}