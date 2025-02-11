<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;
    protected $table = 'cinemas';
    protected $fillable = [
        'name',
        'address',
        'city_id'
    ];
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'cinema_movie', 'cinema_id', 'movie_id');
    }
}
