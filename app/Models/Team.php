<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'logo', 'club_history'];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}