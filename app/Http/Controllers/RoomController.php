<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /**
     * Membuat room baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'control_mode' => ['nullable', 'in:host_only,everyone'],
            'allow_add_song' => ['nullable', 'boolean'],
            'allow_chat' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        do {
            $code = strtoupper(Str::random(6));
        } while (Room::where('code', $code)->exists());

        $room = Room::create([
            'host_id' => $user->id,
            'code' => $code,
            'name' => $request->name,
            'control_mode' => $request->control_mode ?? 'host_only',
            'allow_add_song' => $request->boolean('allow_add_song', true),
            'allow_chat' => $request->boolean('allow_chat', true),
            'is_active' => true,
        ]);

        RoomUser::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'role' => 'host',
            'is_online' => true,
        ]);

        return response()->json([
            'message' => 'Room berhasil dibuat.',
            'room' => $room->load('roomUsers'),
        ], 201);
    }

    /**
     * Bergabung ke room menggunakan kode.
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:10'],
        ]);

        $user = $request->user();

        $room = Room::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (! $room) {
            return response()->json([
                'message' => 'Room tidak ditemukan atau sudah tidak aktif.',
            ], 404);
        }

        $existingMember = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingMember) {
            if (! $existingMember->is_online) {
                $existingMember->update([
                    'is_online' => true,
                    'left_at' => null,
                ]);
            }

            return response()->json([
                'message' => 'Kamu sudah berada di room ini.',
                'room' => $room->load('members'),
            ]);
        }

        RoomUser::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'role' => 'member',
            'is_online' => true,
        ]);

        return response()->json([
            'message' => 'Berhasil bergabung ke room.',
            'room' => $room->load('members'),
        ]);
    }

    /**
     * Menampilkan detail room.
     */
    public function show(Room $room)
    {
        return response()->json([
            'room' => $room->load([
                'host',
                'members',
                'queue.song',
                'queue.addedBy',
            ]),
        ]);
    }

    /**
     * Mengubah data room (Host saja).
     */
    public function update(Request $request, Room $room)
    {
        $user = $request->user();

        if ($user && $room->host_id !== $user->id) {
            return response()->json(['message' => 'Hanya host yang dapat mengedit room ini.'], 403);
        }

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'control_mode' => ['nullable', 'in:host_only,everyone'],
            'allow_add_song' => ['nullable', 'boolean'],
            'allow_chat' => ['nullable', 'boolean'],
        ]);

        $room->update(array_filter([
            'name' => $request->input('name'),
            'control_mode' => $request->input('control_mode'),
            'allow_add_song' => $request->has('allow_add_song') ? $request->boolean('allow_add_song') : null,
            'allow_chat' => $request->has('allow_chat') ? $request->boolean('allow_chat') : null,
        ], fn ($val) => ! is_null($val)));

        return response()->json([
            'message' => 'Room berhasil diperbarui.',
            'room' => $room->fresh()->load(['host', 'members']),
        ]);
    }

    /**
     * Menghapus / menonaktifkan room (Host saja).
     */
    public function destroy(Request $request, Room $room)
    {
        $user = $request->user();

        if ($user && $room->host_id !== $user->id) {
            return response()->json(['message' => 'Hanya host yang dapat menghapus room ini.'], 403);
        }

        $room->update(['is_active' => false]);
        $room->delete();

        return response()->json([
            'message' => 'Room berhasil dihapus.',
        ]);
    }
}
