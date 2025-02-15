<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShowTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'showtimes';
    protected $fillable = [
        'show_time',
        'total_seat',
        'is_sold',
        'screen',
        'schedule_id',
        'room_id'
    ];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
