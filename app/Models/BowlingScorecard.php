<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BowlingScorecard extends Model
{
    protected $table = 'bowling_scorecards';

    protected $fillable = [
        'fixture_id',           // Added for ScoringController support
        'team_id',              // Added for ScoringController support
        'innings_id', 
        'player_id', 
        'balls_thrown',         // Legacy structural tracking
        'balls_bowled',         // Added to match ScoringController mutations
        'overs_bowled',         // Added to store the exact float value (e.g. 1.2, 5.0)
        'maidens', 
        'runs_conceded', 
        'wickets_taken', 
        'wides_conceded', 
        'no_balls_conceded'
    ];

    /**
     * Relationship: The player this bowling scorecard belongs to.
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Convert raw balls thrown into cricket overs display notation (e.g., 15 balls -> 2.3 overs)
     * Fallback options added to guarantee support across all dynamic views.
     */
    public function getFormattedOversAttribute()
    {
        // If controller has already pre-calculated and stored overs_bowled, use it directly
        if (!is_null($this->overs_bowled)) {
            return number_format($this->overs_bowled, 1);
        }

        $rawBalls = $this->balls_bowled ?? ($this->balls_thrown ?? 0);
        
        $overs = floor($rawBalls / 6);
        $balls = $rawBalls % 6;
        
        return $overs . '.' . $balls;
    }

    /**
     * Calculate bowling economy rate dynamically
     */
    public function getEconomyAttribute()
    {
        $rawBalls = 0;

        // Extract raw ball count from whichever source column is present
        if (!is_null($this->overs_bowled)) {
            $wholeOvers = floor($this->overs_bowled);
            $fractionalBalls = round(($this->overs_bowled - $wholeOvers) * 10);
            $rawBalls = ($wholeOvers * 6) + $fractionalBalls;
        } else {
            $rawBalls = $this->balls_bowled ?? ($this->balls_thrown ?? 0);
        }

        if ($rawBalls == 0) {
            return 0.00;
        }
        
        return round(($this->runs_conceded / ($rawBalls / 6)), 2);
    }
}