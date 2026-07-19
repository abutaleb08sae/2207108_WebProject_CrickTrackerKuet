<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentaryLog extends Model
{
    protected $fillable = ['fixture_id', 'innings_number', 'over_number', 'ball_type', 'runs_scored', 'description'];
}