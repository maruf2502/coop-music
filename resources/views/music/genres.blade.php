@extends('layouts.music')

@section('title', 'VibeMusic - Genre Lagu')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto pb-8">

    <!-- HEADER BANNER -->
    <div class="bg-gradient-to-r from-purple-950 via-zinc-900 to-indigo-950 border border-purple-500/20 p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <span class="px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-full inline-block mb-3">Koleksi Musik</span>
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center gap-3">
                <span>🎵</span> Genre & Suasana Musik
            </h1>
            <p class="text-zinc-400 text-sm max-w-xl">Pilih genre musik untuk melihat 10 lagu paling populer saat ini secara instan tanpa meninggalkan halaman genre.</p>
        </div>

        @if($activeGenre)
        <div class="bg-zinc-900/90 border border-indigo-500/30 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-lg shrink-0">
            <span class="text-2xl">{{ $activeGenre['icon'] }}</span>
            <div>
                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Genre Terpilih</p>
                <h3 class="text-sm font-extrabold text-indigo-400">{{ $activeGenre['name'] }}</h3>
            </div>
        </div>
        @endif
    </div>

    <!-- GENRE CARDS GRID -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($genres as $genre)
        @php
            $isSelected = ($selectedQuery && strtolower($selectedQuery) === strtolower($genre['query']));
        @endphp
        <a href="{{ route('music.genres', ['g' => $genre['query']]) }}" 
           class="group relative overflow-hidden h-36 rounded-3xl bg-gradient-to-br {{ $genre['gradient'] }} p-4 flex flex-col justify-between shadow-xl transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl border {{ $isSelected ? 'border-2 border-white scale-[1.02] shadow-indigo-500/40' : 'border-white/10' }}">
            
            <div class="flex items-center justify-between z-10">
                <span class="text-2xl p-2 bg-black/20 backdrop-blur-md rounded-2xl border border-white/10 group-hover:scale-110 transition-transform">
                    {{ $genre['icon'] }}
                </span>
                @if($isSelected)
                <span class="text-[9px] font-extrabold uppercase tracking-wider text-zinc-950 bg-white px-2 py-0.5 rounded-full shadow-md">
                    Aktif ✓
                </span>
                @endif
            </div>

            <div class="z-10">
                <h3 class="text-sm font-extrabold text-white drop-shadow-md group-hover:translate-x-1 transition-transform">
                    {{ $genre['name'] }}
                </h3>
                <p class="text-[10px] text-white/80 font-medium">Lihat 10 Lagu →</p>
            </div>

            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
        </a>
        @endforeach
    </div>

    <!-- TOP 10 POPULAR SONGS SECTION FOR SELECTED GENRE -->
    @if($activeGenre)
    <section class="pt-6 space-y-4 border-t border-zinc-800/80">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl">{{ $activeGenre['icon'] }}</span>
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight flex items-center gap-2">
                        <span>10 Lagu Populer Teratas</span>
                        <span class="text-indigo-400 font-normal">({{ $activeGenre['name'] }})</span>
                    </h2>
                    <p class="text-xs text-zinc-400">Ringan & cepat • Hanya 10 lagu populer terbaik saat ini</p>
                </div>
            </div>

            <a href="{{ route('music.genres') }}" class="text-xs font-semibold text-zinc-400 hover:text-white transition">
                Tampilkan Semua Genre
            </a>
        </div>

        @if(count($genreSongs) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
            @foreach($genreSongs as $index => $song)
            @php
                $sTitle = $song['title'] ?? 'Lagu Populer';
                $sArtist = is_string($song['artist'] ?? null) ? $song['artist'] : 'Artis';
                $sCover = is_string($song['thumbnail'] ?? null) && !empty($song['thumbnail'])
                    ? $song['thumbnail'] 
                    : 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300';
                $sYtId = $song['youtube_id'] ?? '';
            @endphp
            <div onclick="playSong('{{ addslashes($sTitle) }}', '{{ addslashes($sArtist) }}', '{{ $sCover }}', '{{ $sYtId }}')"
                 class="group bg-zinc-900/60 hover:bg-zinc-800/90 border border-zinc-800/80 p-3 rounded-2xl flex items-center gap-4 cursor-pointer transition-all duration-200 hover:border-indigo-500/40 hover:scale-[1.01] shadow-md">
                
                <span class="text-xs font-black text-indigo-400 font-mono w-5 text-center shrink-0">
                    #{{ $index + 1 }}
                </span>

                <img src="{{ $sCover }}" onerror="this.src='https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300'" alt="{{ $sTitle }}" 
                     class="w-12 h-12 rounded-xl object-cover shadow-md shrink-0 border border-zinc-700/50">

                <div class="flex-1 min-w-0">
                    <h3 class="text-xs font-bold text-white truncate group-hover:text-indigo-400 transition">{{ $sTitle }}</h3>
                    <p class="text-[11px] text-zinc-400 truncate mt-0.5">{{ $sArtist }}</p>
                </div>

                <button class="w-8 h-8 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center opacity-80 group-hover:opacity-100 transition shadow-lg shrink-0">
                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-zinc-900/40 border border-zinc-800/60 p-8 rounded-2xl text-center space-y-2">
            <span class="text-2xl">⏳</span>
            <h4 class="text-sm font-bold text-white">Memuat 10 Lagu Populer {{ $activeGenre['name'] }}...</h4>
            <p class="text-xs text-zinc-400">Silakan tunggu sejenak atau klik genre lainnya.</p>
        </div>
        @endif
    </section>
    @else
    <div class="bg-zinc-900/30 border border-zinc-800/40 p-6 rounded-2xl text-center">
        <p class="text-xs text-zinc-400">💡 Klik salah satu kartu genre di atas untuk langsung melihat 10 lagu paling populer tanpa berpindah halaman.</p>
    </div>
    @endif

</div>
@endsection