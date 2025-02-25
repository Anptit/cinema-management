<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rooms';
    protected $fillable = [
        'name',
        'total_seat',
        'available_seats'
    ];
    public function seat()
    {
        return $this->hasMany(Seat::class, 'room_id', 'id'); 
    }
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'room_id', 'id');
    }
    public function name()
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtolower($value)
        );
    }
}
