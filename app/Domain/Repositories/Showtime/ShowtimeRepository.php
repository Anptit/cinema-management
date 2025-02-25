<?php

namespace App\Domain\Repositories\Showtime;

use App\Domain\Models\Movie;
use App\Domain\Repositories\RepositoryBase;

class ShowtimeRepository extends RepositoryBase implements IShowtimeRepository
{
    public function getModel()
    {
        return \App\Domain\Models\Showtime::class;
    }

    public function getRunningTime(int $scheduleId)
    {
        return Movie::join('schedules', 'schedules.movie_id', '=', 'movies.id')
                    ->where('schedules.id', '=', $scheduleId)
                    ->first();
    }
}