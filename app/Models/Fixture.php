<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = ['team_one_id', 'team_two_id', 'match_datetime', 'venue', 'status'];

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
}