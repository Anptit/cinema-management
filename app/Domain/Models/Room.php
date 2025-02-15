<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rooms';
    protected $fillable = [
        'name',
        'total_seat'
    ];
    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'room_id', 'id'); 
    }
    public function showtime()
    {
        return $this->hasOne(Showtime::class, 'room_id', 'id');
    }
}
