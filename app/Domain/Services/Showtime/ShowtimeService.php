<?php

namespace App\Domain\Services\Showtime;

use App\Domain\Models\Movie;
use App\Domain\Models\Room;
use App\Domain\Models\Schedule;
use App\Domain\Models\ShowTime;
use App\Domain\Repositories\Showtime\IShowtimeRepository;
use App\Domain\Services\Showtime\IShowtimeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ShowtimeService implements IShowtimeService
{
    private IShowtimeRepository $showtimeRepository;

    public function __construct(IShowtimeRepository $showtimeRepository)
    {
        $this->showtimeRepository = $showtimeRepository;
    }

    public function create(array $request)
    {
        $schedule = Schedule::find($request['schedule_id']);
        if (empty($schedule)) 
        {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $movie = Movie::find($schedule->movie_id);
        if (empty($movie)) 
        {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $room = Room::find($request['room_id']);
        if (empty($room)) 
        {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $existsShowtime = ShowTime::where('schedule_id', $request['schedule_id'])
                            ->where('room_id', $request['room_id'])
                            ->orderBy('show_time', 'desc')
                            ->first();

        $endtime = $this->calculateEndTime($existsShowtime);
        
        if ($endtime < date_create($request['show_time'])->format('H:i:s')) {
            $showtime = ShowTime::create($request);
        } else {
            dd($endtime, date_create($request['show_time']));
            return response()->json(['message' => 'Showtime already exists'], 400);
        }

        if (empty($existsShowtime)) {
            $showtime = ShowTime::create($request);
        }

        return $showtime;
    }

    public function update(int $id, array $request)
    {
        $showtime = Showtime::find($id);
        if (empty($showtime)) {
            return new \Exception("Show time not found", 404);
        }

        $attribute = array_filter([
            'show_time' => $request['show_time'] ?? null,
            'schedule_id' => $request['schedule_id'] ?? null,
            'room_id' => $request['room_id'] ?? null
        ], function ($value, $key) use ($showtime) {
            return $value != null && $value != $showtime->$key;
        }, ARRAY_FILTER_USE_BOTH);

        if (empty($attribute))
        {
            return [
                'message' => 'No changes detected',
                'data' => $showtime
            ];
        }

        $schedule = Schedule::find($request['schedule_id'] ?? $showtime->schedule_id);
        $movie = Movie::find($schedule->movie_id);
        $checkShowtime = Showtime::where('schedule_id', $schedule->id)
                                ->where(function (Builder $query) use ($movie, $request) {
                                    $query->where(function (Builder $subquery) use ($movie, $request) {
                                        $subquery->whereTime('show_time', '>', $request['show_time'])
                                                ->whereTime('show_time', '<', Carbon::parse($request['show_time'])->addMinutes($movie->running_time));
                                    })
                                    ->orWhere(function (Builder $subquery2) use ($movie, $request) {
                                        $subquery2->whereTime('show_time', '<', $request['show_time'])
                                                ->where('show_time', '>', Carbon::parse($request['show_time'])->subMinutes($movie->running_time));
                                    });
                                })
                                ->exists();
        
        if ($checkShowtime) {
            return [
                'message' => 'Showtime is exists',
                'data' => $showtime
            ];
        }
        $showtime->update($attribute);

        return [
            'message' => "Showtime is updated",
            'data' => $showtime
        ];
    }

    public function delete(int $id)
    {
        $showtime = Showtime::find($id);
        if (empty($showtime)) {
            return new \Exception("Show time not found", 404);
        }

        $showtime->delete();
        return $showtime;
    }

    public function calculateEndTime(ShowTime $showtime)
    {
        $formatTime = date_create($showtime->show_time);
        $getRunningTime = $this->showtimeRepository->getRunningTime($showtime->schedule_id)->running_time;

        return $formatTime->modify("+$getRunningTime minutes")->format('H:i:s');
    }
}
