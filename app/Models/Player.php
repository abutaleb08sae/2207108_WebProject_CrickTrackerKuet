<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id', 
        'name', 
        'short_name',
        'student_id', // <-- ADDED FOR MASS ASSIGNMENT SAFETY
        'photo_path', 
        'image_path', // Kept for safety migration alignment
        'role', 
        'nationality', 
        'batting_style', 
        'bowling_style', 
        'date_of_birth', 
        'birth_date', // Kept for safety migration alignment
        'height', 
        'jersey_number', 
        'debut_date', 
        // 'biography' REMOVED - Missing column in database schema
        
        // Career Stat Tracking Columns
        'matches_played',
        'total_runs',
        'highest_score',
        'batting_average',
        'batting_strike_rate',
        'fifties',
        'hundreds',
        'wickets_taken',
        'best_bowling_figures',
        'bowling_economy',
        'bowling_average',
        'five_wicket_hauls',
        'catches',
        'stumpings'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'birth_date' => 'date',
        'debut_date' => 'date',
        'matches_played' => 'integer',
        'total_runs' => 'integer',
        'highest_score' => 'integer',
        'batting_average' => 'decimal:2',
        'batting_strike_rate' => 'decimal:2',
        'fifties' => 'integer',
        'hundreds' => 'integer',
        'wickets_taken' => 'integer',
        'bowling_economy' => 'decimal:2',
        'bowling_average' => 'decimal:2',
        'five_wicket_hauls' => 'integer',
        'catches' => 'integer',
        'stumpings' => 'integer',
    ];

    /**
     * Get the team that owns the player.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the player's career summary aggregates.
     */
    public function careerStats()
    {
        return $this->hasMany(PlayerStat::class);
    }

    /**
     * Get the player's specific batting match performances.
     */
    public function battingScorecards()
    {
        return $this->hasMany(BattingScorecard::class);
    }

    /**
     * Get the player's specific bowling match performances.
     */
    public function bowlingScorecards()
    {
        return $this->hasMany(BowlingScorecard::class);
    }
}