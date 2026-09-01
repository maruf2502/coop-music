<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YouTubeMusicController extends Controller
{
    /**
     * Mencari lagu melalui YouTube Music Service.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'min:1'],
        ]);

        try {
            $response = Http::timeout(30)
                ->get('http://127.0.0.1:8001/search', [
                    'q' => $request->q,
                ]);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'YouTube Music Service gagal merespons.',
                    'detail' => $response->json(),
                ], 502);
            }

            return response()->json(
                $response->json()
            );

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Tidak dapat terhubung ke YouTube Music Service.',
                'detail' => $e->getMessage(),
            ], 502);
        }
    }

    /**
     * Mengambil detail lagu dari YouTube Music Service.
     */
    public function show(string $youtubeId)
    {
        try {
            $response = Http::timeout(30)
                ->get(
                    'http://127.0.0.1:8001/songs/'.urlencode($youtubeId)
                );

            if ($response->failed()) {
                return response()->json([
                    'message' => 'YouTube Music Service gagal merespons.',
                    'detail' => $response->json(),
                ], 502);
            }

            return response()->json(
                $response->json()
            );

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Tidak dapat terhubung ke YouTube Music Service.',
                'detail' => $e->getMessage(),
            ], 502);
        }
    }
}
