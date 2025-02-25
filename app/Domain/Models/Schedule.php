<?php

namespace App\Domain\Models;

use App\Domain\Models\ScheduleShowtime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "schedules";
    protected $fillable = [
        'show_date',
        'movie_id'
    ];
    public function showtimes()
    {
        return $this->belongsToMany(ShowTime::class, 'schedule_showtimes', 'schedule_id', 'showtime_id')
                    ->using(ScheduleShowtime::class)
                    ->withTimestamps();
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }
}
