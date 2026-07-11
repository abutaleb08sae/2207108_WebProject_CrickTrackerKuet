<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchScore extends Model
{
    use HasFactory;

    protected $fillable = ['fixture_id', 'runs', 'wickets', 'balls_bowled', 'current_innings'];

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }
}