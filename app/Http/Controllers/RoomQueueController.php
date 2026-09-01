<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomQueue;
use App\Models\RoomUser;
use App\Models\Song;
use Illuminate\Http\Request;

class RoomQueueController extends Controller
{
    /**
     * Menambahkan lagu ke queue room.
     */
    public function store(Request $request, Room $room)
    {
        $request->validate([
            'song_id' => ['required', 'integer', 'exists:songs,id'],
        ]);

        $user = $request->user();

        // Pastikan user merupakan anggota room.
        $member = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('is_online', true)
            ->first();

        if (! $member) {
            return response()->json([
                'message' => 'Kamu bukan anggota room ini.',
            ], 403);
        }

        // Jika penambahan lagu dimatikan,
        // hanya host yang boleh menambahkan.
        if (! $room->allow_add_song && $member->role !== 'host') {
            return response()->json([
                'message' => 'Penambahan lagu ke queue tidak diizinkan.',
            ], 403);
        }

        $song = Song::findOrFail($request->song_id);

        // Cari posisi terakhir dalam queue.
        $lastPosition = RoomQueue::where('room_id', $room->id)
            ->max('position');

        $position = ($lastPosition ?? 0) + 1;

        $queue = RoomQueue::create([
            'room_id' => $room->id,
            'song_id' => $song->id,
            'added_by' => $user->id,
            'position' => $position,
            'is_played' => false,
        ]);

        return response()->json([
            'message' => 'Lagu berhasil ditambahkan ke queue.',
            'queue' => $queue->load([
                'song',
                'addedBy',
            ]),
        ], 201);
    }

    /**
     * Menampilkan queue room.
     */
    public function index(Room $room)
    {
        $queue = RoomQueue::where('room_id', $room->id)
            ->where('is_played', false)
            ->orderBy('position')
            ->with([
                'song',
                'addedBy',
            ])
            ->get();

        return response()->json([
            'room' => $room,
            'queue' => $queue,
        ]);
    }

    /**
     * Menandai lagu sebagai sudah diputar.
     */
    public function played(
        Request $request,
        Room $room,
        RoomQueue $queue
    ) {
        $user = $request->user();

        // Pastikan queue milik room yang dimaksud.
        if ($queue->room_id !== $room->id) {
            return response()->json([
                'message' => 'Queue tidak ditemukan di room ini.',
            ], 404);
        }

        // Hanya host yang boleh menandai lagu
        // sebagai sudah diputar.
        $member = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('role', 'host')
            ->where('is_online', true)
            ->first();

        if (! $member) {
            return response()->json([
                'message' => 'Hanya host yang dapat mengubah status queue.',
            ], 403);
        }

        $queue->update([
            'is_played' => true,
        ]);

        return response()->json([
            'message' => 'Lagu ditandai sebagai sudah diputar.',
            'queue' => $queue->load([
                'song',
                'addedBy',
            ]),
        ]);
    }

    /**
     * Menghapus lagu dari queue.
     */
    public function destroy(
        Request $request,
        Room $room,
        RoomQueue $queue
    ) {
        $user = $request->user();

        // Pastikan queue milik room yang dimaksud.
        if ($queue->room_id !== $room->id) {
            return response()->json([
                'message' => 'Queue tidak ditemukan di room ini.',
            ], 404);
        }

        // Pastikan user merupakan anggota room.
        $member = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('is_online', true)
            ->first();

        if (! $member) {
            return response()->json([
                'message' => 'Kamu bukan anggota room ini.',
            ], 403);
        }

        // Host boleh menghapus semua queue.
        // Member hanya boleh menghapus lagu
        // yang dia tambahkan sendiri.
        if (
            $member->role !== 'host' &&
            $queue->added_by !== $user->id
        ) {
            return response()->json([
                'message' => 'Kamu tidak dapat menghapus lagu ini.',
            ], 403);
        }

        // Simpan posisi sebelum queue dihapus.
        $deletedPosition = $queue->position;

        $queue->delete();

        // Rapikan posisi queue setelah penghapusan.
        RoomQueue::where('room_id', $room->id)
            ->where('position', '>', $deletedPosition)
            ->decrement('position');

        return response()->json([
            'message' => 'Lagu berhasil dihapus dari queue.',
        ]);
    }

    /**
     * Mengubah urutan queue.
     *
     * Hanya host yang boleh melakukan reorder.
     */
    public function reorder(Request $request, Room $room)
    {
        $request->validate([
            'queue_id' => [
                'required',
                'integer',
                'exists:room_queue,id',
            ],
            'position' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $user = $request->user();

        // Pastikan user adalah host yang sedang online.
        $member = RoomUser::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('role', 'host')
            ->where('is_online', true)
            ->first();

        if (! $member) {
            return response()->json([
                'message' => 'Hanya host yang dapat mengubah urutan queue.',
            ], 403);
        }

        // Pastikan queue benar-benar milik room tersebut.
        $queue = RoomQueue::where('id', $request->queue_id)
            ->where('room_id', $room->id)
            ->first();

        if (! $queue) {
            return response()->json([
                'message' => 'Queue tidak ditemukan di room ini.',
            ], 404);
        }

        $oldPosition = $queue->position;
        $newPosition = $request->position;

        // Jumlah queue dalam room.
        $maxPosition = RoomQueue::where('room_id', $room->id)
            ->count();

        // Jangan izinkan posisi melebihi jumlah queue.
        $newPosition = min($newPosition, $maxPosition);

        // Tidak perlu melakukan apa-apa jika posisi sama.
        if ($oldPosition !== $newPosition) {

            // Queue dipindahkan ke posisi lebih atas.
            if ($newPosition < $oldPosition) {

                RoomQueue::where('room_id', $room->id)
                    ->where('id', '!=', $queue->id)
                    ->whereBetween(
                        'position',
                        [$newPosition, $oldPosition - 1]
                    )
                    ->increment('position');

            } else {

                // Queue dipindahkan ke posisi lebih bawah.
                RoomQueue::where('room_id', $room->id)
                    ->where('id', '!=', $queue->id)
                    ->whereBetween(
                        'position',
                        [$oldPosition + 1, $newPosition]
                    )
                    ->decrement('position');
            }

            // Simpan posisi baru queue.
            $queue->update([
                'position' => $newPosition,
            ]);
        }

        return response()->json([
            'message' => 'Urutan queue berhasil diubah.',
            'queue' => $queue->fresh()->load([
                'song',
                'addedBy',
            ]),
        ]);
    }
}
