<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BattingScorecard extends Model
{
    protected $table = 'batting_scorecards';

    protected $fillable = [
        'fixture_id',             // Added for ScoringController support
        'team_id',                // Added for ScoringController support
        'innings_id', 
        'player_id', 
        'dismissal_description',  // Tracks dismissal summary strings
        'out_status',             // Added for explicit status check fallbacks
        'bowler_id', 
        'fielder_id', 
        'runs',                   // Legacy runs column support
        'runs_scored',            // Added to match ScoringController mutations
        'balls_faced', 
        'fours',                  // Legacy fours support
        'fours_hit',              // Added to match ScoringController mutations
        'sixes',                  // Legacy sixes support
        'sixes_hit'               // Added to match ScoringController mutations
    ];

    /**
     * Relationship: The player this scorecard belongs to.
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Relationship: The bowler involved in the dismissal.
     */
    public function bowler()
    {
        return $this->belongsTo(Player::class, 'bowler_id');
    }

    /**
     * Relationship: The fielder involved in the dismissal.
     */
    public function fielder()
    {
        return $this->belongsTo(Player::class, 'fielder_id');
    }

    /**
     * Dynamic Attribute: Calculate Batting Strike Rate.
     * Accessible via $card->strike_rate
     *
     * @return float
     */
    public function getStrikeRateAttribute()
    {
        if ($this->balls_faced == 0) {
            return 0.00;
        }

        // Check if runs_scored is present, fallback to structural runs column if needed
        $totalRuns = $this->runs_scored ?? ($this->runs ?? 0);

        return round(($totalRuns / $this->balls_faced) * 100, 2);
    }
}