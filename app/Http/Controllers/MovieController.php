<?php

namespace App\Http\Controllers;

use App\Domain\Services\MovieService;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use Exception;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public MovieService $movieService;
    public function __construct(MovieService $movieService)
    {
        $this->middleware('auth:api');
        $this->movieService = $movieService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $movies = $this->movieService->getAll($request);
        return response()->json([
            'data' => $movies,
            'message' => 'Movies retrieved successfully'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request)
    {
        try {
            $movie = $this->movieService->create($request->all());
          
            return response()->json([
                'message' => 'Movie created successfully',
                'data' => [
                    'movie' => $movie['movie']
                ]
            ], status: 201);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], status: 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $movie = $this->movieService->getById((int)$id);
            return response()->json([
                'data' => $movie,
                'message' => 'Movie retrieved successfully'
            ], 200);
        } 
        catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], status: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, string $id)
    {
        try {
            $movie = $this->movieService->update((int)$id, $request->all());
            return response()->json([
                'data' => $movie,
                'message' => 'Movie updated successfully'
            ], 200);
        } 
        catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], status: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->movieService->delete((int)$id);
            return response()->json([
                'message' => 'Movie deleted successfully'
            ], 200);
        } 
        catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], status: 500);
        }
    }
}
