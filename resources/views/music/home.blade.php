@extends('layouts.music')

@section('title', 'VibeMusic - Beranda')

@section('content')
<div class="space-y-10">

    <!-- HERO / WELCOME BANNER -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 p-8 border border-indigo-500/20 shadow-2xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 max-w-xl">
            <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-full inline-block mb-3">Selamat Datang 👋</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-2">Dengarkan Musik & Nikmati Room Bersama</h1>
            <p class="text-zinc-300 text-sm mb-6 leading-relaxed">Temukan ribuan lagu rekomendasi, buat playlist favoritmu, atau buat listening room bareng teman secara realtime.</p>
            <div class="flex items-center gap-3">
                <a href="{{ route('music.search') }}" class="px-5 py-2.5 rounded-xl bg-white text-zinc-950 font-bold text-xs hover:bg-zinc-200 transition shadow-lg flex items-center gap-2">
                    <span>🔍</span> Cari Lagu Sekarang
                </a>
                <a href="{{ route('music.rooms') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition border border-indigo-400/30 flex items-center gap-2">
                    <span>🎧</span> Masuk Room Live
                </a>
            </div>
        </div>
    </div>

    <!-- 1. TERUSLAH MENDENGARKAN (Continue Listening) -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">▶️</span>
                <h2 class="text-xl font-bold text-white tracking-tight">Teruslah Mendengarkan</h2>
            </div>
            <a href="{{ route('music.library') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">Lihat semua →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $continueListening = [
                    ['title' => '夜に駆ける (Racing Into The Night)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'duration' => '04:22', 'ytid' => 'by4SYYWlhEs'],
                    ['title' => 'アイドル (Idol)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj', 'duration' => '03:34', 'ytid' => 'ZRtdQ81jPUQ'],
                    ['title' => '怪物 (Monster)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'duration' => '03:26', 'ytid' => 'dy90tA3TT1c'],
                ];
            @endphp

            @foreach($continueListening as $item)
            <div onclick="playSong('{{ $item['title'] }}', '{{ $item['artist'] }}', '{{ $item['cover'] }}', '{{ $item['ytid'] ?? '' }}')" 
                 class="group bg-zinc-900/60 hover:bg-zinc-800/80 border border-zinc-800/80 p-3 rounded-2xl flex items-center gap-4 cursor-pointer transition-all duration-300 hover:border-indigo-500/40 hover:scale-[1.01] shadow-md">
                <img src="{{ $item['cover'] }}" alt="{{ $item['title'] }}" class="w-14 h-14 rounded-xl object-cover shadow-md group-hover:shadow-indigo-500/20">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-white truncate group-hover:text-indigo-400 transition">{{ $item['title'] }}</h3>
                    <p class="text-xs text-zinc-400 truncate">{{ $item['artist'] }}</p>
                </div>
                <span class="text-xs font-mono text-zinc-500 mr-2">{{ $item['duration'] }}</span>
                <button class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg shadow-indigo-600/30">
                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
            </div>
            @endforeach
        </div>
    </section>

    <!-- 🌟 PERSONALIZED RECOMMENDATION SECTION BASED ON FOLLOWED ARTISTS -->
    <section id="followedArtistsSection" class="hidden space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">⭐</span>
                <h2 class="text-xl font-bold text-white tracking-tight flex items-center gap-2">
                    <span>Berdasarkan Artis Yang Anda Ikuti</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] uppercase font-mono tracking-wider">Algoritma Personal</span>
                </h2>
            </div>
            <span id="followedCountBadge" class="text-xs font-semibold text-zinc-400">0 Artis Di-follow</span>
        </div>

        <div id="followedArtistsCards" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <!-- Dynamically populated via JS based on followed artists in localStorage -->
        </div>
    </section>

    <!-- 2. REKOMENDASI LAGU -->

    <section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">✨</span>
                <h2 class="text-xl font-bold text-white tracking-tight">Rekomendasi Spesial Untukmu</h2>
            </div>
            <a href="{{ route('music.search') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">Jelajahi →</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @if(count($recommendations) > 0)
                @foreach($recommendations as $rec)
                @php
                    $recTitle = $rec['title'] ?? 'Lagu';
                    $recArtist = is_string($rec['artist'] ?? null) 
                        ? $rec['artist'] 
                        : (isset($rec['artists'][0]['name']) ? $rec['artists'][0]['name'] : 'Artis');
                    $recCover = is_string($rec['thumbnail'] ?? null) && !empty($rec['thumbnail'])
                        ? $rec['thumbnail'] 
                        : (isset($rec['thumbnails'][0]['url']) ? $rec['thumbnails'][0]['url'] : 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300');
                    $recYtId = $rec['youtube_id'] ?? ($rec['videoId'] ?? '');
                @endphp
                <div onclick="playSong('{{ addslashes($recTitle) }}', '{{ addslashes($recArtist) }}', '{{ $recCover }}', '{{ $recYtId }}')"
                     class="group bg-zinc-900/50 hover:bg-zinc-800/90 border border-zinc-800/60 p-3.5 rounded-2xl cursor-pointer transition-all duration-300 hover:border-indigo-500/40 hover:-translate-y-1 shadow-lg">
                    <div class="relative mb-3 aspect-square rounded-xl overflow-hidden bg-zinc-950">
                        <img src="{{ $recCover }}" 
                             onerror="this.src='https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300'"
                             alt="{{ $recTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-xl shadow-indigo-600/50 transform scale-75 group-hover:scale-100 transition-transform">
                                <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-white truncate mb-1 group-hover:text-indigo-400 transition">{{ $recTitle }}</h3>
                    <p class="text-xs text-zinc-400 truncate">{{ $recArtist }}</p>
                </div>
                @endforeach

            @else
                @php
                    $fallbackRecs = [
                        ['title' => 'ハルカ (Haruka)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/1MHUMxR-99XhjSFYzgOewhG32w91DIW7RTW90EEIh1CkPcMD2R5SNoajkt-24Hq6Zs2lPcgtMHdQZMxL1A=w120-h120-l90-rj', 'ytid' => 'VwGZFnQFRGM'],
                        ['title' => '祝福 (The Blessing)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/yZS5cGvyeDKPkCI5cmlD3p_O_CCjE4N6msFalbOEhuRytLta0QgzjPbJbzlh2KGzmEiG6sLafrzEf1Vx=w120-h120-l90-rj', 'ytid' => '3eytpBOkOFA'],
                        ['title' => '勇者 (Yuusha)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj', 'ytid' => 'OIBODIPC_8Y'],
                        ['title' => '群青 (Gunjou)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'ytid' => 'vELh3Q6562U'],
                        ['title' => 'たぶん (Probably)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'ytid' => '8iuLXODzL00'],
                    ];
                @endphp
                @foreach($fallbackRecs as $rec)
                <div onclick="playSong('{{ $rec['title'] }}', '{{ $rec['artist'] }}', '{{ $rec['cover'] }}', '{{ $rec['ytid'] ?? '' }}')"
                     class="group bg-zinc-900/50 hover:bg-zinc-800/90 border border-zinc-800/60 p-3.5 rounded-2xl cursor-pointer transition-all duration-300 hover:border-indigo-500/40 hover:-translate-y-1 shadow-lg">
                    <div class="relative mb-3 aspect-square rounded-xl overflow-hidden bg-zinc-950">
                        <img src="{{ $rec['cover'] }}" alt="{{ $rec['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-xl shadow-indigo-600/50 transform scale-75 group-hover:scale-100 transition-transform">
                                <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-white truncate mb-1 group-hover:text-indigo-400 transition">{{ $rec['title'] }}</h3>
                    <p class="text-xs text-zinc-400 truncate">{{ $recArtist ?? $rec['artist'] }}</p>
                </div>
                @endforeach
            @endif
        </div>
    </section>

    <!-- 3. PLAYLIST KAMU -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">📚</span>
                <h2 class="text-xl font-bold text-white tracking-tight">Playlist Kamu</h2>
            </div>
            <a href="{{ route('music.library') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">Kelola Playlist →</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $myPlaylists = [
                    ['name' => 'Favorit Saya', 'icon' => '❤️', 'bg' => 'from-rose-500 to-pink-600', 'count' => '12 lagu', 'title' => '夜に駆ける', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'ytid' => 'by4SYYWlhEs'],
                    ['name' => 'Night Vibe Playlist', 'icon' => '🌙', 'bg' => 'from-indigo-600 to-purple-800', 'count' => '8 lagu', 'title' => 'たぶん (Probably)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'ytid' => '8iuLXODzL00'],
                    ['name' => 'Energi & Workout', 'icon' => '⚡', 'bg' => 'from-amber-500 to-orange-600', 'count' => '15 lagu', 'title' => 'Kick Back', 'artist' => 'Kenshi Yonezu', 'cover' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=150', 'ytid' => 'M2cckDmNLMI'],
                    ['name' => 'Chill & Relaxation', 'icon' => '☕', 'bg' => 'from-teal-600 to-emerald-800', 'count' => '6 lagu', 'title' => 'ハルカ (Haruka)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/1MHUMxR-99XhjSFYzgOewhG32w91DIW7RTW90EEIh1CkPcMD2R5SNoajkt-24Hq6Zs2lPcgtMHdQZMxL1A=w120-h120-l90-rj', 'ytid' => 'VwGZFnQFRGM'],
                ];
            @endphp

            @foreach($myPlaylists as $pl)
            <div onclick="playSong('{{ $pl['title'] }}', '{{ $pl['artist'] }}', '{{ $pl['cover'] }}', '{{ $pl['ytid'] }}')" 
                 class="group bg-zinc-900/40 hover:bg-zinc-800/80 border border-zinc-800/60 p-4 rounded-2xl cursor-pointer transition-all duration-300 hover:border-indigo-500/30 flex flex-col justify-between h-36 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-gradient-to-br {{ $pl['bg'] }} rounded-full opacity-20 blur-xl group-hover:opacity-40 transition-opacity"></div>
                <div class="flex items-center justify-between z-10">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $pl['bg'] }} text-2xl flex items-center justify-center shadow-md">
                        {{ $pl['icon'] }}
                    </div>
                    <span class="text-[11px] font-semibold text-zinc-400 bg-zinc-950/60 px-2 py-1 rounded-md border border-zinc-800">{{ $pl['count'] }}</span>
                </div>
                <div class="z-10">
                    <h3 class="text-base font-bold text-white group-hover:text-indigo-400 transition">{{ $pl['name'] }}</h3>
                    <p class="text-xs text-zinc-400">Putar koleksi ▶</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- 4. FAVORIT DI DENGARKAN & MIRIP DENGAN LAGU -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- FAVORIT DI DENGARKAN -->
        <section class="bg-zinc-900/40 border border-zinc-800/60 p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔥</span>
                    <h2 class="text-lg font-bold text-white">Favorit Sering Didengarkan</h2>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $favorites = [
                        ['title' => 'アイドル (Idol)', 'artist' => 'YOASOBI', 'count' => '42x diputar', 'cover' => 'https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj', 'ytid' => 'ZRtdQ81jPUQ'],
                        ['title' => '夜に駆ける (Racing Into The Night)', 'artist' => 'YOASOBI', 'count' => '38x diputar', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'ytid' => 'by4SYYWlhEs'],
                        ['title' => '怪物 (Monster)', 'artist' => 'YOASOBI', 'count' => '25x diputar', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'ytid' => 'dy90tA3TT1c'],
                    ];
                @endphp
                @foreach($favorites as $idx => $fav)
                <div onclick="playSong('{{ $fav['title'] }}', '{{ $fav['artist'] }}', '{{ $fav['cover'] }}', '{{ $fav['ytid'] ?? '' }}')" 
                     class="flex items-center gap-4 p-2.5 rounded-xl hover:bg-zinc-800/60 cursor-pointer transition">
                    <span class="text-sm font-extrabold text-zinc-500 w-5 text-center">0{{ $idx + 1 }}</span>
                    <img src="{{ $fav['cover'] }}" class="w-11 h-11 rounded-lg object-cover">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-white truncate">{{ $fav['title'] }}</h4>
                        <p class="text-xs text-zinc-400">{{ $fav['artist'] }}</p>
                    </div>
                    <span class="text-xs text-indigo-400 font-medium bg-indigo-500/10 px-2 py-1 rounded-md border border-indigo-500/20">{{ $fav['count'] }}</span>
                </div>
                @endforeach
            </div>
        </section>

        <!-- MIRIP DENGAN LAGU (SIMILAR SONGS MIX) -->
        <section class="bg-zinc-900/40 border border-zinc-800/60 p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🎶</span>
                    <h2 class="text-lg font-bold text-white">Mirip Dengan "夜に駆ける"</h2>
                </div>
                <span class="text-xs text-zinc-400">Rekomendasi Algoritma</span>
            </div>
            <div class="space-y-3">
                @php
                    $similar = [
                        ['title' => '廻廻奇譚 (Kaikai Kitan)', 'artist' => 'Eve', 'cover' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=150', 'ytid' => '1tk1P1MfsCs'],
                        ['title' => 'Kick Back', 'artist' => 'Kenshi Yonezu', 'cover' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=150', 'ytid' => 'M2cckDmNLMI'],
                        ['title' => '残響散歌 (Zankyosanka)', 'artist' => 'Aimer', 'cover' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=150', 'ytid' => 'tLQLa6lM36I'],
                    ];
                @endphp
                @foreach($similar as $sim)
                <div onclick="playSong('{{ $sim['title'] }}', '{{ $sim['artist'] }}', '{{ $sim['cover'] }}', '{{ $sim['ytid'] ?? '' }}')" 
                     class="flex items-center gap-4 p-2.5 rounded-xl hover:bg-zinc-800/60 cursor-pointer transition">
                    <img src="{{ $sim['cover'] }}" class="w-11 h-11 rounded-lg object-cover">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-white truncate">{{ $sim['title'] }}</h4>
                        <p class="text-xs text-zinc-400">{{ $sim['artist'] }}</p>
                    </div>
                    <button class="px-3 py-1 bg-zinc-800 hover:bg-indigo-600 hover:text-white text-zinc-300 text-xs font-semibold rounded-lg transition">
                        Putar
                    </button>
                </div>
                @endforeach
            </div>
        </section>
    </div>

    <!-- 5. ARTIS FAVORIT -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🎙️</span>
                <h2 class="text-xl font-bold text-white tracking-tight">Artis Populer & Favorit</h2>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @php
                $artists = [
                    ['name' => 'YOASOBI', 'role' => 'J-Pop Duo', 'image' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'topSong' => 'アイドル (Idol)', 'ytid' => 'ZRtdQ81jPUQ'],
                    ['name' => 'Eve', 'role' => 'Singer-Songwriter', 'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=300', 'topSong' => '廻廻奇譚 (Kaikai Kitan)', 'ytid' => '1tk1P1MfsCs'],
                    ['name' => 'Kenshi Yonezu', 'role' => 'Artist / Producer', 'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=300', 'topSong' => 'Kick Back', 'ytid' => 'M2cckDmNLMI'],
                    ['name' => 'Aimer', 'role' => 'J-Pop Vocalist', 'image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=300', 'topSong' => '残響散歌 (Zankyosanka)', 'ytid' => 'tLQLa6lM36I'],
                    ['name' => 'Official HIGE DANdism', 'role' => 'Pop Rock Band', 'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300', 'topSong' => 'Pretender', 'ytid' => 'TQ8WlA2GXbk'],
                ];
            @endphp

            @foreach($artists as $art)
            <div onclick="playSong('{{ $art['topSong'] }}', '{{ $art['name'] }}', '{{ $art['image'] }}', '{{ $art['ytid'] }}')" 
                 class="group bg-zinc-900/40 hover:bg-zinc-800/80 border border-zinc-800/60 p-4 rounded-2xl text-center cursor-pointer transition-all duration-300 hover:border-indigo-500/40">
                <img src="{{ $art['image'] }}" alt="{{ $art['name'] }}" 
                     class="w-24 h-24 rounded-full object-cover mx-auto mb-3 shadow-md border-2 border-zinc-700 group-hover:border-indigo-500 transition-colors">
                <h3 class="text-sm font-bold text-white group-hover:text-indigo-400 transition truncate">{{ $art['name'] }}</h3>
                <p class="text-xs text-zinc-400 truncate">{{ $art['role'] }}</p>
            </div>
            @endforeach
        </div>
    </section>


</div>

@push('scripts')
<script>
    window.refreshFollowedArtistSection = function() {
        const section = document.getElementById('followedArtistsSection');
        const container = document.getElementById('followedArtistsCards');
        const badge = document.getElementById('followedCountBadge');

        const followed = JSON.parse(localStorage.getItem('followed_artists') || '[]');

        if (!followed || followed.length === 0) {
            if (section) section.classList.add('hidden');
            return;
        }

        if (section) section.classList.remove('hidden');
        if (badge) badge.textContent = `${followed.length} Artis Di-follow`;

        if (container) {
            container.innerHTML = '';
            followed.forEach(artist => {
                const card = document.createElement('div');
                card.className = 'group bg-gradient-to-br from-indigo-950/40 via-zinc-900 to-zinc-900 hover:bg-zinc-800 border border-indigo-500/30 p-3.5 rounded-2xl cursor-pointer transition-all duration-300 hover:scale-[1.02] shadow-lg';
                card.onclick = function() {
                    playSong(`Lagu Populer ${artist}`, artist, 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800');
                };
                card.innerHTML = `
                    <div class="relative mb-3 aspect-square rounded-xl overflow-hidden bg-zinc-950">
                        <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2 right-2 px-2 py-0.5 rounded-full bg-emerald-500 text-zinc-950 text-[10px] font-extrabold shadow-md">
                            ✓ Following
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-white truncate mb-1 group-hover:text-indigo-400 transition">${artist}</h3>
                    <p class="text-xs text-emerald-400 font-medium truncate">Di-follow oleh Anda</p>
                `;
                container.appendChild(card);
            });
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.refreshFollowedArtistSection === 'function') {
            window.refreshFollowedArtistSection();
        }
    });

    // Run initial refresh
    if (typeof window.refreshFollowedArtistSection === 'function') {
        window.refreshFollowedArtistSection();
    }
</script>
@endpush
@endsection