@extends('layouts.music')

@section('title', 'VibeMusic - Cari Lagu')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- SEARCH HEADER & INPUT -->
    <div class="bg-gradient-to-r from-zinc-900 via-indigo-950/40 to-zinc-900 border border-zinc-800/80 p-8 rounded-3xl shadow-xl">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center gap-3">
                <span>🔍</span> Cari Lagu & Musik
            </h1>
            <p class="text-zinc-400 text-sm mb-6">Temukan jutaan lagu dari YouTube Music, dengarkan langsung atau tambahkan ke Room secara realtime.</p>
            
            <form action="{{ route('music.search') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Ketik judul lagu, penyanyi, atau kata kunci..." 
                           class="w-full bg-zinc-950 border border-zinc-700/80 rounded-2xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner">
                    <svg class="w-5 h-5 text-zinc-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-3.5 rounded-2xl text-sm transition shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                    <span>Cari</span>
                </button>
            </form>

            <!-- QUICK SUGGESTIONS -->
            <div class="flex items-center gap-2 mt-4 flex-wrap">
                <span class="text-xs text-zinc-400">Trending:</span>
                @foreach(['YOASOBI', 'J-Pop Hits', 'Lofi Chill', 'K-Pop Top 50', 'Rock Classics'] as $tag)
                <a href="{{ route('music.search', ['q' => $tag]) }}" 
                   class="text-xs bg-zinc-800/60 hover:bg-indigo-600/20 hover:text-indigo-400 hover:border-indigo-500/30 border border-zinc-700/60 text-zinc-300 px-3 py-1 rounded-full transition">
                    {{ $tag }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ERROR ALERT IF ANY -->
    @if($error)
    <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-center gap-3">
        <span>⚠️</span>
        <span>{{ $error }}</span>
    </div>
    @endif

    <!-- RESULTS SECTION -->
    @if($query)
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Hasil Pencarian: <span class="text-indigo-400">"{{ $query }}"</span></h2>
            <span class="text-xs text-zinc-400">{{ count($results) }} lagu ditemukan</span>
        </div>

        @if(count($results) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($results as $item)
            @php
                $title = $item['title'] ?? 'Unknown Title';
                
                $artist = is_string($item['artist'] ?? null) 
                    ? $item['artist'] 
                    : (isset($item['artists'][0]['name']) ? $item['artists'][0]['name'] : 'Unknown Artist');
                
                $album = is_string($item['album'] ?? null) 
                    ? $item['album'] 
                    : (isset($item['album']['name']) ? $item['album']['name'] : 'Single');
                
                $cover = is_string($item['thumbnail'] ?? null) && !empty($item['thumbnail'])
                    ? $item['thumbnail'] 
                    : (isset($item['thumbnails'][0]['url']) ? $item['thumbnails'][0]['url'] : 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=150');
                
                $youtubeId = $item['youtube_id'] ?? ($item['videoId'] ?? '');
                
                $duration = is_numeric($item['duration'] ?? null)
                    ? gmdate('i:s', $item['duration'])
                    : ($item['duration'] ?? '3:30');
            @endphp
            <div data-title="{{ $title }}" data-artist="{{ $artist }}" data-cover="{{ $cover }}" data-youtube-id="{{ $youtubeId }}" onclick="playSongFromBtn(this)"
                 class="group bg-zinc-900/50 hover:bg-zinc-800/80 border border-zinc-800/80 p-3.5 rounded-2xl flex items-center gap-4 cursor-pointer transition-all duration-300 hover:border-indigo-500/40 shadow-md">
                <img src="{{ $cover }}" alt="{{ $title }}" 
                     onerror="this.src='https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=150'"
                     class="w-16 h-16 rounded-xl object-cover shadow-md flex-shrink-0 border border-zinc-800">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-white truncate group-hover:text-indigo-400 transition">{{ $title }}</h3>
                    <p class="text-xs text-zinc-400 truncate">{{ $artist }} • <span class="text-zinc-500">{{ $album }}</span></p>
                    <span class="inline-block mt-1 text-[10px] font-mono text-zinc-500 bg-zinc-950 px-2 py-0.5 rounded border border-zinc-800">{{ $duration }}</span>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button data-title="{{ $title }}" data-artist="{{ $artist }}" data-cover="{{ $cover }}" data-youtube-id="{{ $youtubeId }}" onclick="event.stopPropagation(); playSongFromBtn(this)" 
                            class="w-10 h-10 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-600/30 transition transform hover:scale-105"
                            title="Putar Lagu Ini">
                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </button>
                    <a href="{{ route('music.rooms') }}" onclick="event.stopPropagation()" title="Tambah ke Room Queue" 
                       class="w-10 h-10 rounded-full bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white flex items-center justify-center border border-zinc-700/60 transition">
                        🎧
                    </a>
                </div>
            </div>

            @endforeach
        </div>

        @else
        <div class="text-center py-16 bg-zinc-900/20 border border-zinc-800/40 rounded-3xl">
            <span class="text-4xl mb-2 block">🔍</span>
            <p class="text-zinc-400 text-sm">Tidak ada lagu yang ditemukan untuk kata kunci ini.</p>
        </div>
        @endif
    </div>
    @else
    <!-- DEFAULT POPULAR DISCOVERY -->
    <div>
        <h2 class="text-lg font-bold text-white mb-4">Paling Sering Dicari Hari Ini</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $discoveries = [
                    ['title' => 'Yoasobi Full Album', 'query' => 'YOASOBI', 'icon' => '🌸'],
                    ['title' => 'Lofi Beats to Study', 'query' => 'Lofi Beats', 'icon' => '☕'],
                    ['title' => 'J-Pop Viral TikTok', 'query' => 'J-Pop Viral', 'icon' => '🔥'],
                    ['title' => 'Anime Opening OST', 'query' => 'Anime OST', 'icon' => '⚡'],
                ];
            @endphp
            @foreach($discoveries as $disc)
            <a href="{{ route('music.search', ['q' => $disc['query']]) }}" 
               class="p-5 rounded-2xl bg-zinc-900/40 hover:bg-zinc-800/80 border border-zinc-800/60 transition group text-center flex flex-col items-center">
                <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">{{ $disc['icon'] }}</span>
                <span class="text-sm font-bold text-white group-hover:text-indigo-400 transition">{{ $disc['title'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection