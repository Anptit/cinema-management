<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleShowtime extends Pivot
{
    use HasFactory, SoftDeletes;
    protected $table = 'schedule_showtimes';
    protected $fillable = ['schedule_id', 'showtime_id'];
}
