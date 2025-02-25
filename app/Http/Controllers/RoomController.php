<?php

namespace App\Http\Controllers;

use App\Domain\Models\Room;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::query();

        if (!empty($query->is_full))
        {
            $query->where('available_seats', 0);
        }

        if (!empty($query->is_available))
        {
            $query->where('available_seats', '<>', 0);
        }

        if (!empty($query->sortBy)) 
        {
            $query->orderBy($query->sortBy ?? 'created_by', $query->sortDirection ?? 'asc');
        }

        $query->where('deleted_at', null);
        
        return response()->json($query->paginate($request->perPage ?? 10), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'unique:rooms,name'],
                'total_seat' => ['required', 'integer'],
            ]);

            $attribute = [
                'name' => strtolower($validated['name']),
                'total_seat' => $validated['total_seat'],
                'available_seats' => $validated['total_seat']
            ];

            $room = Room::create($attribute);

            return response()->json([
                'message' => 'Room is created successfully',
                'data' => $room
            ]);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $existsRoom = Room::find($id);
            if (empty($existsRoom)) 
            {
                return response()->json(['message' => 'Room not found'], 404);
            }

            if ($existsRoom->available_seats !== $existsRoom->total_seat)
            {
                return response()->json(['message' => 'Total seats are changed'], 500);
            }
    
            $validated = $request->validate([
                'name' => ['string', 'unique:rooms,name'],
                'total_seat' => ['integer'],
            ]);
    
            $attribute = [
                'name' => strtolower($validated['name']) ?? $existsRoom->name,
                'total_seat' => $validated['total_seat'] ?? $existsRoom->total_seat,
                'available_seats' => $validated['total_seat'] ?? $existsRoom->available_seats
            ];
    
            $existsRoom->update($attribute);
    
            return response()->json([
                'message' => 'Room is created successfully',
                'data' => $existsRoom
            ]);
        } catch (Exception $e)  {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $room = Room::find($id);
            if (empty($room))
            {
                return response()->json(['message' => 'Room not found'], 500);
            }

            DB::table('schedule_showtimes as ss')
                ->join('showtimes as s', 'ss.showtime_id', 's.id')
                ->where('s.room_id', $room->id)
                ->update(['ss.deleted_at' => Carbon::now()]);

            $room->seat()->delete();
            $room->showtimes()->delete();
            $room->delete();
            return response()->json(['message' => 'Room is deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
