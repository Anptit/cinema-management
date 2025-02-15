<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovieGenre extends Pivot
{
    use HasFactory, SoftDeletes;
    protected $table = 'movie_genres';
    protected $fillable = ['movie_id', 'genre_id'];
}
