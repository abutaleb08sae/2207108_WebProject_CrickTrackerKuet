<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $fillable = [
        'team_id', 'name', 'role', 'student_id', 
        'matches_played', 'total_runs', 'total_wickets'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}