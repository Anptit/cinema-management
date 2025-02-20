<?php

namespace App\Domain\Services\Showtime;

use App\Domain\Models\ShowTime;
use Illuminate\Http\Request;

interface IShowtimeService
{
    public function create(array $request);
    public function update(int $id, array $request);
    public function delete(int $id);
    public function calculateEndTime(ShowTime $showtime);
}