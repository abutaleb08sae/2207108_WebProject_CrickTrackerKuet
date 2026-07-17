<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'team1_id',
        'team2_id',
        'winner_id',
        'player_of_the_match_id',
        'match_number',
        'match_date',
        'venue',
        'status',
        'team1_score',
        'team2_score',
        'result_description'
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function team1(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    public function playerOfTheMatch(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_of_the_match_id');
    }
}