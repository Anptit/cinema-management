<?php

namespace App\Http\Controllers;

use App\Domain\Services\Showtime\ShowtimeService;
use App\Http\Requests\StoreShowtimeRequest;
use App\Http\Requests\UpdateShowtimeRequest;
use Exception;
use Illuminate\Http\Request;

class ShowTimeController extends Controller
{
    private ShowtimeService $showtimeService;

    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }

    public function store(StoreShowtimeRequest $request)
    {
        try {
            $showtime = $this->showtimeService->create($request->all());
            return response()->json($showtime, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(string $id, UpdateShowtimeRequest $request)
    {
        try {
            $showtime = $this->showtimeService->update((int)$id, $request->all());
            return response()->json($showtime, 200);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $showtime = $this->showtimeService->delete((int)$id);
            return response()->json([
                'message' => 'Delete showtime successfully',
                'data' => $showtime
            ]);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
