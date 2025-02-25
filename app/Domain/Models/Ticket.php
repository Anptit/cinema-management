<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tickets';
    protected $fillable = [
        'price',
        'user_id',
        'seat_id',
        'movie_id',
    ];
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'id');
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
