<?php

namespace App\Domain\Repositories\Showtime;

use App\Domain\Models\Movie;

interface IShowtimeRepository
{
    public function getRunningTime(int $scheduleId);
}