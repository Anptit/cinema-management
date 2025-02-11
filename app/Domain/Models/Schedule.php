<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = "schedules";
    protected $fillale = [
        'sneaky_show',
        'start_date',
        'end_date',
        'movie_id'
    ];
    public function showtimes()
    {
        return $this->hasMany(ShowTime::class, 'schedule_id', 'id');
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }
}
