<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'episode_id', 'opening_crawl', 'director', 'producer', 'release_date', 'url'
    ];
    public function characters()
    {
        return $this->belongsToMany(Character::class);
    }

    public function planets()
    {
        return $this->belongsToMany(Planet::class, 'movie_planet');
    }

    public function starships()
    {
        return $this->belongsToMany(Starship::class);
    }
}
