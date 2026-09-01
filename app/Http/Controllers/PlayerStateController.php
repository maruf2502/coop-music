<?php

namespace App\Http\Controllers;

use App\Models\PlayerState;
use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;

class PlayerStateController extends Controller
{
    /**
     * Menampilkan player state room.
     */
    public function show(Request $request, Room $room)
    {
        $user = $request->user();

        $member = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('is_online', true)
            ->first();

        if (! $member) {
            return response()->json([
                'message' => 'Kamu bukan anggota room ini.',
            ], 403);
        }

        $state = PlayerState::where('room_id', $room->id)
            ->with('song')
            ->first();

        return response()->json([
            'room' => $room,
            'player_state' => $state,
        ]);
    }

    /**
     * Membuat atau memperbarui player state room.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'song_id' => ['nullable', 'integer', 'exists:songs,id'],
            'status' => ['required', 'in:playing,paused,stopped'],
            'position' => ['required', 'numeric', 'min:0'],
        ]);

        $user = $request->user();

        $member = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('role', 'host')
            ->where('is_online', true)
            ->first();

        if (! $member) {
            return response()->json([
                'message' => 'Hanya host yang dapat mengubah player state.',
            ], 403);
        }

        $state = PlayerState::updateOrCreate(
            [
                'room_id' => $room->id,
            ],
            [
                'song_id' => $request->song_id,
                'status' => $request->status,
                'position' => $request->position,
                'updated_at_server' => now(),
            ]
        );

        return response()->json([
            'message' => 'Player state berhasil diperbarui.',
            'player_state' => $state->load('song'),
        ]);
    }
}
