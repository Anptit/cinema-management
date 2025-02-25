<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'seats';
    protected $fillable = [
        'name',
        'genre',
        'is_sold',
        'room_id'
    ];
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'seat_id', 'id');
    }
}
