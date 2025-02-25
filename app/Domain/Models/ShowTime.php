<?php

namespace App\Domain\Models;

use App\Domain\Models\ScheduleShowtime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShowTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'showtimes';
    protected $fillable = [
        'show_time',
        'room_id'
    ];
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_showtimes', 'showtime_id', 'schedule_id')
                    ->using(ScheduleShowtime::class)
                    ->withTimestamps();;
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
