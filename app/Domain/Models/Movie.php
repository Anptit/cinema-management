<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "movies";
    protected $fillable = [
        'name',
        'description',
        'genre',
        'running_time',
        'cast',
        'director',
        'language',
        'version',
        'release_date',
        'sneaky_show',
        'has_sneaky_show',
        'start_date',
        'end_date',
        'trending'
    ];
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres', 'movie_id', 'genre_id')
                    ->using(MovieGenre::class)
                    ->withTimestamps();
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'movie_id', 'id');
    }
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'movie_id', 'id');
    }
    public function cinemas()
    {
        return $this->belongsToMany(Cinema::class, 'cinema_movie', 'movie_Id', 'cinema_id');

    }
}
