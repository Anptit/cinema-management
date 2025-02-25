<?php

namespace App\Domain\Services\Showtime;

use App\Domain\Models\Movie;
use App\Domain\Models\Room;
use App\Domain\Models\Schedule;
use App\Domain\Models\ScheduleShowtime;
use App\Domain\Models\ShowTime;
use App\Domain\Repositories\Showtime\IShowtimeRepository;
use App\Domain\Services\Showtime\IShowtimeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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

        $newShowTime = Carbon::parse($request['show_time']);
        $movieDuration = $movie->running_time;
        $newEndTime = $newShowTime->copy()->addMinutes($movieDuration);

        $existsShowtime = ShowTime::where('room_id', $request['room_id'])
        ->where('show_time', '=', $newShowTime)
        ->whereHas('schedules', function ($query) use ($request) {
            $query->where('schedule_id', $request['schedule_id']);
        })
        ->exists();

    if ($existsShowtime) {
        return response()->json(['message' => 'Showtime is exists'], 400);
    }


        // Kiểm tra suất chiếu trước đó trong cùng phòng
        $prevShowtime = ShowTime::where('room_id', $request['room_id'])
            ->whereHas('schedules', function ($query) use ($request) {
                $query->where('schedule_id', $request['schedule_id']);
            })
            ->where('show_time', '<', $newShowTime)
            ->orderBy('show_time', 'desc')
            ->first();

        if ($prevShowtime) {
            $prevMovie = Movie::find($prevShowtime->schedules->first()->movie_id);
            $prevEndTime = Carbon::parse($prevShowtime->show_time)->addMinutes($prevMovie->running_time);

            // Nếu suất chiếu mới chồng lên suất chiếu trước đó → Không cho phép
            if ($newShowTime < $prevEndTime) {
                return response()->json(['message' => 'Thời gian chiếu mới bị chồng lên suất chiếu trước đó'], 400);
            }
        }

        $nextShowtime = ShowTime::where('room_id', $request['room_id'])
            ->whereHas('schedules', function ($query) use ($request) {
                $query->where('schedule_id', $request['schedule_id']);
            })
            ->where('show_time', '>', $newShowTime)
            ->orderBy('show_time', 'asc')
            ->first();

        if ($nextShowtime) {
            $nextStartTime = Carbon::parse($nextShowtime->show_time);

            // Nếu suất chiếu mới kết thúc sau khi suất chiếu tiếp theo bắt đầu → Không cho phép
            if ($newEndTime > $nextStartTime) {
                return response()->json(['message' => 'Thời gian chiếu mới bị chồng lên suất chiếu sau đó'], 400);
            }
        }
        
        $showtime = ShowTime::create([
            'show_time' => $newShowTime,
            'room_id' => $request['room_id']
        ]);
    
        $schedule->showtimes()->attach($showtime->id);
    
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
                                ->where('room_id', $request['room_id'] ?? $showtime->room_id)
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

    public function calculateEndTime(ShowTime $showtime, int $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        $formatTime = date_create(date_create($schedule->show_date)->format('Y-m-d') . ' ' . $showtime->show_time);
        $getRunningTime = $this->showtimeRepository->getRunningTime($scheduleId)->running_time;

        return $formatTime->modify("+$getRunningTime minutes");
    }
}
