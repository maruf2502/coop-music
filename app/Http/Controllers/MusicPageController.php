<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MusicPageController extends Controller
{
    public function home()
    {
        // Try fetching dynamic recommendations from YT Music service if available
        $recommendations = [];
        try {
            $response = Http::timeout(5)->get('http://127.0.0.1:8001/search', ['q' => 'popular hits 2026']);
            if ($response->successful()) {
                $recommendations = array_slice($response->json('results', []), 0, 8);
            }
        } catch (\Throwable $e) {
            $recommendations = [];
        }

        // Database items if any exist
        $dbSongs = Song::limit(10)->get();
        $playlists = Playlist::limit(6)->get();
        $recentHistories = History::with('song')->latest()->limit(5)->get();

        return view('music.home', [
            'recommendations' => $recommendations,
            'dbSongs' => $dbSongs,
            'playlists' => $playlists,
            'recentHistories' => $recentHistories,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = [];
        $error = null;

        if ($query) {
            try {
                $response = Http::timeout(30)
                    ->get('http://127.0.0.1:8001/search', [
                        'q' => $query,
                    ]);

                if ($response->successful()) {
                    $results = $response->json('results', []);
                } else {
                    // Automatic retry if python service was sleeping
                    sleep(1);
                    $retryResponse = Http::timeout(30)
                        ->get('http://127.0.0.1:8001/search', ['q' => $query]);

                    if ($retryResponse->successful()) {
                        $results = $retryResponse->json('results', []);
                    } else {
                        $error = 'Koneksi pencarian sibuk. Silakan coba sebentar lagi.';
                    }
                }

            } catch (\Throwable $e) {
                // Retry attempt on socket timeout
                try {
                    $retryResponse = Http::timeout(30)
                        ->get('http://127.0.0.1:8001/search', ['q' => $query]);

                    if ($retryResponse->successful()) {
                        $results = $retryResponse->json('results', []);
                    } else {
                        $error = 'Koneksi pencarian sibuk. Silakan coba lagi.';
                    }
                } catch (\Throwable $retryErr) {
                    $error = 'Koneksi pencarian sibuk. Silakan coba lagi.';
                }
            }
        }

        if ($request->wantsJson() && str_contains($request->header('Accept', ''), 'application/json')) {
            return response()->json([
                'query' => $query,
                'results' => $results,
                'error' => $error,
            ]);
        }

        return view('music.search', [
            'query' => $query,
            'results' => $results,
            'error' => $error,
        ]);
    }

    public function genres(Request $request)
    {
        $selectedQuery = $request->input('g');

        $genres = [
            ['name' => 'J-Pop Hits', 'icon' => '🎌', 'gradient' => 'from-rose-600 to-pink-900', 'query' => 'J-Pop'],
            ['name' => 'Lofi Chill', 'icon' => '☕', 'gradient' => 'from-indigo-600 to-slate-900', 'query' => 'Lofi Chill'],
            ['name' => 'Anime OST', 'icon' => '⚡', 'gradient' => 'from-amber-500 to-purple-900', 'query' => 'Anime OST'],
            ['name' => 'Pop Hits', 'icon' => '🌟', 'gradient' => 'from-cyan-600 to-blue-900', 'query' => 'Pop Hits'],
            ['name' => 'Rock & Metal', 'icon' => '🎸', 'gradient' => 'from-red-700 to-zinc-900', 'query' => 'Rock Hits'],
            ['name' => 'K-Pop Viral', 'icon' => '🇰🇷', 'gradient' => 'from-fuchsia-600 to-pink-950', 'query' => 'K-Pop'],
            ['name' => 'EDM & Party', 'icon' => '🎧', 'gradient' => 'from-emerald-600 to-teal-950', 'query' => 'EDM Party'],
            ['name' => 'Acoustic Relax', 'icon' => '🌿', 'gradient' => 'from-amber-700 to-stone-900', 'query' => 'Acoustic'],
            ['name' => 'Hip-Hop & R&B', 'icon' => '🔥', 'gradient' => 'from-violet-700 to-purple-950', 'query' => 'Hip Hop'],
            ['name' => 'Indie Vibe', 'icon' => '🎨', 'gradient' => 'from-orange-600 to-amber-950', 'query' => 'Indie Vibe'],
        ];

        $genreSongs = [];
        $activeGenre = null;

        if ($selectedQuery) {
            foreach ($genres as $g) {
                if (strtolower($g['query']) === strtolower($selectedQuery) || strtolower($g['name']) === strtolower($selectedQuery)) {
                    $activeGenre = $g;
                    break;
                }
            }
            if (! $activeGenre) {
                $activeGenre = ['name' => $selectedQuery, 'icon' => '🎵', 'query' => $selectedQuery, 'gradient' => 'from-indigo-600 to-purple-900'];
            }

            try {
                $response = Http::timeout(25)->get('http://127.0.0.1:8001/search', ['q' => $selectedQuery]);
                if ($response->successful()) {
                    // EXACTLY 10 POPULAR SONGS (LIGHTWEIGHT & FAST)
                    $genreSongs = array_slice($response->json('results', []), 0, 10);
                }
            } catch (\Throwable $e) {
            }
        }

        return view('music.genres', [
            'genres' => $genres,
            'selectedQuery' => $selectedQuery,
            'activeGenre' => $activeGenre,
            'genreSongs' => $genreSongs,
        ]);
    }

    public function rooms()
    {
        $rooms = Room::with(['host'])
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('music.rooms', compact('rooms'));
    }

    public function roomDetail($id)
    {
        $room = Room::with(['host', 'members', 'queue.song', 'queue.addedBy'])
            ->where('id', $id)
            ->orWhere('code', strtoupper($id))
            ->firstOrFail();

        return view('music.room-detail', compact('room'));
    }

    public function library()
    {
        $playlists = Playlist::latest()->get();
        $likedSongs = Song::latest()->get(); // demo fallback
        $histories = History::with('song')->latest()->get();

        return view('music.library', compact('playlists', 'likedSongs', 'histories'));
    }

    public function stream(string $youtubeId)
    {
        try {
            $response = Http::timeout(15)->get('http://127.0.0.1:8001/stream/'.urlencode($youtubeId));
            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Throwable $e) {
        }

        return response()->json(['error' => 'Audio stream unavailable', 'youtube_id' => $youtubeId], 404);
    }
}
