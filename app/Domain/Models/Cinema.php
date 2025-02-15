<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    use HasFactory, SoftDeletes;
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
