<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowTime extends Model
{
    use HasFactory;
    protected $table = 'showtimes';
    protected $fillable = [
        'show_time',
        'total_seat',
        'is_sold',
        'screen',
        'schedule_id'
    ];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
    public function seats()
    {
        return $this->hasMany(Seat::class, 'showtime_id', 'id');
    }
}
