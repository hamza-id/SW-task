<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    public function characters()
    {
        return $this->belongsToMany(Character::class);
    }

    public function planets()
    {
        return $this->belongsToMany(Planet::class);
    }

    public function starships()
    {
        return $this->belongsToMany(Starship::class);
    }
}
