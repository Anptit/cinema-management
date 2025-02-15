<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "genres";
    protected $fillable = ['name'];
    public function movies() 
    {
        return $this->belongsToMany(Movie::class, 'movie_genres', 'genre_id', 'movie_id')
                    ->using(MovieGenre::class)
                    ->withTimestamps();
    }

    public function name()
    {
        return Attribute::make(
            get: fn(string $value) => strtolower($value), 
            set: fn(string $value) => ucfirst($value)
        );
    }
}
