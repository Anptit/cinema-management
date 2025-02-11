<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = "movies";
    protected $fillable = [
        'name',
        'running_time',
        'cast',
        'director',
        'language',
        'version',
        'release_date'
    ];
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_movie', 'movie_id', 'genre_id');
    }
    public function schedule()
    {
        return $this->hasone(Schedule::class, 'movie_id', 'id');
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
