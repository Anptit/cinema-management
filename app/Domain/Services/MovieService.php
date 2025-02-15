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

use function PHPUnit\Framework\isEmpty;

class MovieService
{
    public $movieRepository;
    public function __construct(IMovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;   
    }

    public function getAll(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $today = Carbon::now();
        $query = Movie::query();
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

        return $query->paginate($perPage);
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
        $daterange = new DatePeriod($start_date, $interval ,$end_date);

        foreach($daterange as $date){
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

    public function getById(int $id)
    {
        $movie = Movie::with(['schedules' => function (Builder $query) {
                    $query->whereDate('show_date', '>=', Carbon::now())
                            ->orderBy('show_date', 'asc')
                            ->with('showtimes');
                }])
                ->findOrFail($id);

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