<?php

namespace App\Domain\Services;

use App\Domain\Enums\ShowtimeStatus;
use App\Domain\Models\Genre;
use App\Domain\Models\Movie;
use App\Domain\Models\MovieGenre;
use App\Domain\Models\Schedule;
use App\Domain\Repositories\Movie\IMovieRepository;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MovieService
{
    public $movieRepository;
    public function __construct(IMovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getAll(Request $request)
    {
        $query = Movie::query();
        $today = Carbon::now();
        $orderBy = $request->input('order_by', 'asc');

        if (!empty($request->input('search'))) {
            $query->where('title', 'like', '%' . $request->query('search') . '%');
        }

        if (!empty($request->input('sortBy'))) {
            $query->orderBy($request->query('sortBy'), $orderBy);
        }

        if (!empty($request->input('per_page'))) {
            $perPage = $request->query('per_page');
        }

        switch (strtolower($request->status)) {
            case ShowtimeStatus::SPECIAL->value:
                $query->where('has_sneaky_show', '=', true)
                    ->whereDate('sneaky_show', '>=', $today);
                break;
            case ShowtimeStatus::UPCOMING->value:
                $query->whereDate('release_date', '>', $today);
                break;
            default:
                $query->whereDate('release_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
        }

        return $query->paginate($perPage ?? 10);
    }

    public function create(array $request)
    {
        $movie = Movie::create($request);
        $genres = explode(',', $request['genre']);

        foreach ($genres as $genre) {
            $get_genre = Genre::whereRaw("LOWER(name) = ?", trim(strtolower($genre)))->first();
            if (!empty($get_genre)) {
                $movie->genres()->attach($get_genre->id);
            } else {
                $new_genre = Genre::create(['name' => trim($genre)]);
                $movie->genres()->attach($new_genre->id);
            }
        }

        $start_date = date_create($request['release_date']);
        $end_date = date_create($request['end_date'])->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $daterange = new DatePeriod($start_date, $interval, $end_date);

        foreach ($daterange as $date) {
            Schedule::create([
                'show_date' => $date->format('Y-m-d H:i:s'),
                'movie_id' => $movie->id
            ]);
        }

        return [
            'movie' => $movie,
            'schedules' => $movie->schedules
        ];
    }

    public function getById(int $id, array $request)
    {
        $query = Movie::query()
            ->where('movies.id', $id)
            ->with(['schedules' => function ($scheduleQuery) use ($request) {
              
                if (!empty($request['date_search'])) {
                    $date = Carbon::parse($request['date_search'])->format('Y-m-d');
                    $scheduleQuery->whereDate('show_date', '=', $date);
                }

                $scheduleQuery->whereHas('showtimes', function ($showtimeQuery) use ($request) {
                    if (!empty($request['time_search'])) {
                        $time = Carbon::parse($request['time_search'])->format('H:i:s');
                        $showtimeQuery->whereTime('show_time', '=', $time);
                    }
                });

                $scheduleQuery->with(['showtimes' => function ($showtimeQuery) use ($request) { 
                    
                    if (!empty($request['time_search'])) {
                        $time = Carbon::parse($request['time_search'])->format('H:i:s');
                        $showtimeQuery->whereTime('show_time', '=', $time);
                    }
                }]);

                $scheduleQuery->whereDate('show_date', '>=', Carbon::now()->format('Y-m-d'))
                    ->orderBy('show_date', 'asc');
            }]);

        $movie = $query->first();

        if (!$movie) {
            throw new \Exception('Movie not found', 404);
        }

        return $movie;
    }

    public function update(int $id, array $request)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($request);
        $genres = explode(',', $request['genre']);
        $movie->genres()->detach();
        foreach ($genres as $genre) {
            $get_genre = Genre::whereRaw("LOWER(name) = ?", trim(strtolower($genre)))->first();
            if (!empty($get_genre)) {
                $movie->genres()->attach($get_genre->id);
            } else {
                $new_genre = Genre::create(['name' => trim($genre)]);
                $movie->genres()->attach($new_genre->id);
            }
        }

        return $movie;
    }

    public function delete(int $id)
    {
        $movie = Movie::findOrFail($id);
        MovieGenre::where('movie_id', $id)->delete();
        $movie->schedules()->delete();
        $movie->ticket()->delete();
        $movie->delete();
        return $movie;
    }
}
