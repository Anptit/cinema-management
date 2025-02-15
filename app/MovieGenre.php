<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MovieGenre extends Pivot
{
    use HasFactory;
    protected $table = "movie_genres";
    protected $fillable = ['movie_id', 'genre_id'];

}
