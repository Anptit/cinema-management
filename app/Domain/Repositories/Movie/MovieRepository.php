<?php

namespace App\Domain\Repositories\Movie;

use App\Domain\Repositories\RepositoryBase;

class MovieRepository extends RepositoryBase implements IMovieRepository
{
    public function getModel()
    {
        return \App\Domain\Models\Movie::class;
    }
}