<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $table = 'seats';
    protected $fillable = [
        'genre',
        'status',
        'showtime_id'
    ];
    public function showtime()
    {
        return $this->belongsTo(ShowTime::class, 'showtime_id', 'id');
    }
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'seat_id', 'id');
    }
}
