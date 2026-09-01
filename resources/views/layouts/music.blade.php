<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'VibeMusic - Streaming & Listening Room')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #09090b;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }

        /* ========================================
           CUSTOM RANGE SLIDER (SEEKBAR & VOLUME)
        ======================================== */

        /* Remove default styling */
        input[type="range"] {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            background: transparent;
            cursor: pointer;
            outline: none;
        }

        /* === SEEKBAR === */
        #seekBar {
            height: 14px;
            display: flex;
            align-items: center;
        }

        #seekBar::-webkit-slider-runnable-track {
            height: 4px;
            border-radius: 9999px;
            background: linear-gradient(to right, #6366f1 var(--seek-progress, 0%), #3f3f46 var(--seek-progress, 0%));
            transition: height 0.15s ease;
        }

        #seekBar:hover::-webkit-slider-runnable-track {
            height: 6px;
        }

        #seekBar::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #6366f1;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.6), 0 2px 4px rgba(0,0,0,0.5);
            margin-top: -4.5px;
            opacity: 1;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        #seekBar:hover::-webkit-slider-thumb {
            transform: scale(1.25);
            box-shadow: 0 0 12px rgba(99, 102, 241, 0.9), 0 2px 6px rgba(0,0,0,0.6);
        }

        #seekBar:active::-webkit-slider-thumb {
            transform: scale(1.35);
        }

        /* Firefox */
        #seekBar::-moz-range-track {
            height: 4px;
            border-radius: 9999px;
            background: #3f3f46;
            border: none;
        }

        #seekBar::-moz-range-progress {
            height: 4px;
            border-radius: 9999px;
            background: #6366f1;
        }

        #seekBar:hover::-moz-range-track {
            height: 6px;
        }

        #seekBar:hover::-moz-range-progress {
            height: 6px;
        }

        #seekBar::-moz-range-thumb {
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #6366f1;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.6), 0 2px 4px rgba(0,0,0,0.5);
            opacity: 1;
            transition: transform 0.15s ease;
        }

        #seekBar:hover::-moz-range-thumb {
            transform: scale(1.25);
        }

        /* === VOLUME BAR === */
        #volumeBar {
            height: 14px;
            display: flex;
            align-items: center;
        }

        #volumeBar::-webkit-slider-runnable-track {
            height: 4px;
            border-radius: 9999px;
            background: linear-gradient(to right, #6366f1 var(--vol-progress, 80%), #3f3f46 var(--vol-progress, 80%));
            transition: height 0.15s ease;
        }

        #volumeBar:hover::-webkit-slider-runnable-track {
            height: 5px;
        }

        #volumeBar::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #6366f1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
            margin-top: -3.5px;
            opacity: 1;
            transition: transform 0.15s ease;
        }

        #volumeBar:hover::-webkit-slider-thumb {
            transform: scale(1.2);
        }

        /* Firefox */
        #volumeBar::-moz-range-track {
            height: 4px;
            border-radius: 9999px;
            background: #3f3f46;
            border: none;
        }

        #volumeBar::-moz-range-progress {
            height: 4px;
            border-radius: 9999px;
            background: #6366f1;
        }

        #volumeBar::-moz-range-thumb {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #6366f1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
            opacity: 1;
            transition: transform 0.15s ease;
        }

        #volumeBar:hover::-moz-range-thumb {
            transform: scale(1.2);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-zinc-950 text-zinc-100 h-full flex flex-col overflow-hidden selection:bg-indigo-500 selection:text-white">

<div class="flex flex-1 h-[calc(100vh-90px)] overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-zinc-900/80 backdrop-blur-xl border-r border-zinc-800/60 flex flex-col justify-between flex-shrink-0 h-[calc(100vh-90px)] min-h-0 overflow-y-auto z-20 custom-scrollbar">


        <div class="p-6">
            <!-- LOGO -->
            <a href="{{ route('music.home') }}" class="flex items-center gap-3 group mb-8">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 .895-2 3-2 3 .895 3 2zm12 0c0 1.105-1.343 2-3 2s-3-.895-3-2 .895-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold bg-gradient-to-r from-white via-zinc-200 to-zinc-400 bg-clip-text text-transparent">VibeMusic</span>
                    <span class="block text-[10px] uppercase tracking-widest text-indigo-400 font-semibold">Live Room & Stream</span>
                </div>
            </a>

            <!-- NAV MENU -->
            <nav class="space-y-1">
                <a href="{{ route('music.home') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('music.home') ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/30 shadow-inner' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }}">
                    <span class="text-lg">🏠</span>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('music.search') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('music.search') ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/30 shadow-inner' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }}">
                    <span class="text-lg">🔍</span>
                    <span>Cari Lagu</span>
                </a>

                <a href="{{ route('music.genres') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('music.genres') ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/30 shadow-inner' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }}">
                    <span class="text-lg">🎵</span>
                    <span>Genre Lagu</span>
                </a>

                <a href="{{ route('music.rooms') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('music.rooms*') || request()->routeIs('music.room*') ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/30 shadow-inner' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }}">
                    <span class="text-lg">🎧</span>
                    <span class="flex-1">Listening Room</span>
                    <span class="px-1.5 py-0.5 text-[10px] font-bold bg-pink-500/20 text-pink-400 border border-pink-500/30 rounded-md">LIVE</span>
                </a>

                <a href="{{ route('music.library') }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('music.library') ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/30 shadow-inner' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }}">
                    <span class="text-lg">📚</span>
                    <span>Pustaka Kamu</span>
                </a>
            </nav>
        </div>

        <!-- QUICK ROOM ACTION IN SIDEBAR FOOTER -->
        <div class="p-4 m-4 rounded-2xl bg-gradient-to-br from-indigo-900/30 via-zinc-900 to-purple-900/20 border border-indigo-500/20">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold text-zinc-300">Gabung Room Cepat</span>
            </div>
            <form action="{{ route('music.rooms') }}" method="GET" class="flex gap-1.5">
                <input type="text" name="code" placeholder="Kode (ex: AB12CD)" 
                       class="w-full bg-zinc-950/80 border border-zinc-700/60 rounded-lg px-2.5 py-1.5 text-xs text-white placeholder-zinc-500 uppercase font-mono focus:outline-none focus:border-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                    Join
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 flex flex-col overflow-y-auto bg-gradient-to-b from-zinc-900/60 via-zinc-950 to-zinc-950">
        <!-- TOPBAR -->
        <header class="sticky top-0 z-10 bg-zinc-950/70 backdrop-blur-md px-8 py-4 border-b border-zinc-800/40 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <form action="{{ route('music.search') }}" method="GET" class="relative w-80">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari lagu, artis, atau album..." 
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-full pl-10 pr-4 py-2 text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <svg class="w-4 h-4 text-zinc-500 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </form>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('music.rooms') }}" class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 text-xs font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Buat Room Baru</span>
                </a>

                @auth
                <!-- AUTHENTICATED USER DROPDOWN -->
                <div class="relative group">
                    <button type="button" class="flex items-center gap-2.5 p-1 rounded-full hover:bg-zinc-800/60 transition pr-3 border border-zinc-800/80 bg-zinc-900/60">
                        @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border border-white/10 shadow-md">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 text-white font-bold flex items-center justify-center text-xs shadow-md border border-white/10">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        @endif
                        <span class="text-xs font-bold text-zinc-200 max-w-[100px] truncate hidden md:inline-block">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-zinc-400 group-hover:text-white transition-transform group-hover:rotate-180 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- DROPDOWN MENU -->
                    <div class="absolute right-0 mt-2 w-48 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="px-4 py-2 border-b border-zinc-800/80">
                            <p class="text-[11px] font-bold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-zinc-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('music.library') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-zinc-300 hover:text-white hover:bg-zinc-800/70 transition">
                            <span>📚</span>
                            <span>Pustaka Kamu</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="pt-1 border-t border-zinc-800/80">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition text-left">
                                <span>🚪</span>
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <!-- GUEST LOGIN & REGISTER BUTTONS -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" 
                       class="px-3.5 py-1.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-bold border border-zinc-800 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                        Daftar
                    </a>
                </div>
                @endauth
            </div>
        </header>

        <!-- PAGE BODY CONTENT WITH PERSISTENT PLAYER WRAPPER -->
        <div id="pageContentContainer" class="p-8 pb-12 flex-1">
            @yield('content')
        </div>
    </main>

    <!-- RIGHT NOW PLAYING SIDEBAR (MATCHING SPOTIFY REFERENCE DESIGN) -->
    <aside id="rightSidebar" class="w-80 lg:w-84 bg-zinc-950/95 border-l border-zinc-800/80 flex flex-col h-[calc(100vh-90px)] min-h-0 overflow-y-auto shrink-0 transition-all duration-300 p-4 space-y-4 shadow-2xl z-20 custom-scrollbar">


        <!-- SIDEBAR HEADER -->
        <div class="flex items-center justify-between pb-1">
            <h3 class="text-xs font-bold text-zinc-300 uppercase tracking-wider flex items-center gap-2">
                <span>🎵</span> Sedang Diputar
            </h3>
            <button onclick="toggleRightSidebar()" class="text-zinc-500 hover:text-white transition text-sm p-1" title="Sembunyikan Sidebar">
                ✕
            </button>
        </div>

        <!-- HIGH-RES ALBUM COVER & SONG INFO -->
        <div class="space-y-3">
            <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-zinc-800/80 aspect-square group bg-zinc-900">
                <img id="rightCover" src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80" 
                     alt="Album Cover" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            </div>

            <div class="flex items-start justify-between gap-2 pt-1">
                <div class="min-w-0 flex-1">
                    <h2 id="rightTitle" class="text-base font-bold text-white leading-snug line-clamp-2">Belum Ada Lagu</h2>
                    <p id="rightArtist" class="text-xs text-zinc-400 font-medium truncate mt-0.5">Pilih lagu untuk memutar</p>
                </div>
                <button onclick="togglePlayerLike()" id="rightLikeBtn" class="text-zinc-500 hover:text-rose-500 transition p-1.5 rounded-full hover:bg-zinc-800">
                    <svg id="rightHeartIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-4.5-4.5M12 6.318a4.5 4.5 0 00-4.5-4.5"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- ABOUT THE ARTIST CARD (MATCHING REFERENCE IMAGE 2 & 3) -->
        <div class="bg-zinc-900/90 rounded-2xl border border-zinc-800/80 p-4 space-y-3.5 shadow-xl relative overflow-hidden">
            <h4 class="text-xs font-bold text-white tracking-wider uppercase">About the artist</h4>
            
            <!-- ARTIST BANNER / COVER IMAGE -->
            <div class="h-32 rounded-xl overflow-hidden relative bg-zinc-950 border border-zinc-800">
                <img id="rightArtistBanner" src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-transparent to-transparent"></div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <h3 id="rightArtistName" class="text-sm font-bold text-white flex items-center gap-1.5 truncate">
                            <span>Nama Artis</span>
                            <span class="w-3.5 h-3.5 bg-emerald-500 text-zinc-950 rounded-full flex items-center justify-center text-[9px] font-black">✓</span>
                        </h3>
                        <p id="rightArtistListeners" class="text-[11px] text-zinc-400 font-medium">125,465 monthly listeners</p>
                    </div>
                    
                    <!-- FOLLOW ARTIST BUTTON -->
                    <button id="followArtistBtn" onclick="toggleFollowArtist()" 
                            class="px-4 py-1.5 rounded-full border border-zinc-600 hover:border-white text-xs font-bold text-white hover:bg-white hover:text-zinc-950 transition-all duration-200 shadow-md">
                        Follow
                    </button>
                </div>

                <p id="rightArtistBio" class="text-[11px] text-zinc-300/80 leading-relaxed line-clamp-3">
                    Kategori musik pilihan favorit dengan irama melodi berkualitas tinggi yang menemani harimu.
                </p>
            </div>
        </div>

        <!-- CREDITS CARD (MATCHING REFERENCE IMAGE 3) -->
        <div class="bg-zinc-900/60 rounded-2xl border border-zinc-800/70 p-4 space-y-3 shadow-lg">
            <div class="flex items-center justify-between text-xs font-bold">
                <span class="text-white">Credits</span>
                <span class="text-zinc-500 text-[10px] uppercase cursor-pointer hover:text-white">Show all</span>
            </div>

            <div class="flex items-center justify-between pt-1">
                <div>
                    <h5 id="rightCreditArtist" class="text-xs font-bold text-white">Nama Artis</h5>
                    <p class="text-[10px] text-zinc-400">Main Artist</p>
                </div>
                <button id="creditFollowBtn" onclick="toggleFollowArtist()" class="px-3 py-1 rounded-full border border-zinc-600 text-[11px] font-bold text-white hover:border-white transition">
                    Follow
                </button>
            </div>
        </div>
    </aside>

</div>


<!-- GLOBAL FIXED BOTTOM PLAYER BAR -->
<footer id="playerBar" class="h-[90px] bg-zinc-900 border-t border-zinc-800/80 px-6 flex items-center justify-between z-30 relative shadow-2xl">
    <!-- SONG INFO -->
    <div class="flex items-center gap-3 w-1/4">
        <img id="playerCover" src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=150&auto=format&fit=crop&q=80" 
             alt="Track Cover" class="w-14 h-14 rounded-lg object-cover shadow-md border border-zinc-700/50 flex-shrink-0">
        <div class="overflow-hidden min-w-0">
            <h4 id="playerTitle" class="text-sm font-semibold text-zinc-100 truncate">Belum Ada Lagu</h4>
            <p id="playerArtist" class="text-xs text-zinc-400 truncate">Pilih lagu untuk memutar</p>
        </div>
        
        <!-- LIKE / HEART BUTTON -->
        <button id="playerLikeBtn" class="text-zinc-500 hover:text-rose-500 transition ml-1 p-1.5 rounded-full hover:bg-zinc-800/60" title="Sukai Lagu Ini">
            <svg id="likeHeartIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-4.5-4.5M12 6.318a4.5 4.5 0 00-4.5-4.5"></path>
            </svg>
        </button>

        <!-- ADD TO PLAYLIST BUTTON -->
        <button id="addToPlaylistBtn" onclick="openAddToPlaylistModal()" class="text-zinc-400 hover:text-indigo-400 transition p-1.5 rounded-lg hover:bg-zinc-800/60 text-xs font-bold flex items-center gap-1" title="Tambah ke Playlist">
            <span>📁+</span>
        </button>
    </div>

    <!-- CONTROLS & PROGRESS BAR -->
    <div class="flex flex-col items-center w-2/4 max-w-xl">
        <div class="flex items-center gap-6 mb-2">
            <!-- SHUFFLE -->
            <button id="shuffleBtn" class="text-zinc-400 hover:text-white transition text-xs p-1.5 rounded-md" title="Putar Acak (Shuffle)">
                🔀
            </button>
            
            <!-- PREVIOUS -->
            <button id="prevBtn" class="text-zinc-300 hover:text-white transition" title="Lagu Sebelumnya">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg>
            </button>
            
            <!-- PLAY / PAUSE -->
            <button id="playPauseBtn" class="w-10 h-10 rounded-full bg-white hover:bg-zinc-200 text-zinc-950 flex items-center justify-center shadow-lg transition-transform active:scale-95">
                <svg id="playIcon" class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                <svg id="pauseIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
            </button>
            
            <!-- NEXT -->
            <button id="nextBtn" class="text-zinc-300 hover:text-white transition" title="Lagu Berikutnya">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/></svg>
            </button>
            
            <!-- REPEAT / LOOPING (DIRECT SINGLE SONG LOOPING) -->
            <button id="repeatBtn" class="text-zinc-400 hover:text-white transition text-xs p-1.5 rounded-md flex items-center gap-0.5" title="Ulangi Lagu Ini (Single Song Loop)">
                <span>🔁</span>
                <span id="repeatBadge" class="text-[9px] font-extrabold hidden">1</span>
            </button>
        </div>

        <div class="w-full flex items-center gap-3">
            <span id="currentTime" class="text-[11px] text-zinc-500 font-mono">0:00</span>
            <div class="flex-1 relative group cursor-pointer">
                <input id="seekBar" type="range" min="0" max="100" value="0" step="0.1">
            </div>
            <span id="totalDuration" class="text-[11px] text-zinc-500 font-mono">0:00</span>
        </div>
    </div>

    <!-- VOLUME & EXTRA ACTIONS -->
    <div class="flex items-center justify-end gap-3 w-1/4">
        <a href="{{ route('music.rooms') }}" class="text-zinc-400 hover:text-white transition text-sm" title="Listening Room">🎧</a>
        <div class="flex items-center gap-2">
            <span id="volumeIcon" class="text-zinc-400 text-xs">🔊</span>
            <input id="volumeBar" type="range" min="0" max="100" value="80" class="w-20">
        </div>

        <!-- TOGGLE RIGHT SIDEBAR BUTTON -->
        <button id="toggleRightSidebarBtn" onclick="toggleRightSidebar()" 
                class="p-2 rounded-xl text-indigo-400 bg-indigo-500/15 border border-indigo-500/30 hover:bg-indigo-500/25 transition ml-1" 
                title="Tampilkan / Sembunyikan Detail Lagu (Sidebar)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
            </svg>
        </button>
    </div>
</footer>


<!-- TOP SPA PROGRESS LOADING BAR -->

<div id="spaProgressBar" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-indigo-500 via-pink-500 to-amber-500 z-50 transition-all duration-300 w-0 pointer-events-none"></div>

<!-- ACTIVE OFF-SCREEN YOUTUBE PLAYER CONTAINER -->
<div style="position: fixed; bottom: 0; right: 0; width: 240px; height: 180px; opacity: 0.001; pointer-events: none; z-index: -1;">
    <div id="ytPlayerDiv"></div>
</div>
<!-- OPENTUNE NATIVE DIRECT AUDIO STREAM PLAYER -->
<audio id="nativeAudioPlayer" preload="auto" class="hidden"></audio>



<!-- TOAST NOTIFICATION CONTAINER -->
<div id="toastNotification" class="fixed bottom-24 right-6 z-50 bg-zinc-900 border border-indigo-500/40 text-white text-xs font-bold px-4 py-3 rounded-2xl shadow-2xl transition-all duration-300 transform translate-y-10 opacity-0 pointer-events-none flex items-center gap-2">
    <span id="toastIcon">✨</span>
    <span id="toastMessage">Notifikasi</span>
</div>

<!-- 📁 MODAL ADD TO PLAYLIST -->
<div id="addToPlaylistModal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>📁</span> Tambahkan Lagu ke Playlist
            </h3>
            <button onclick="closeAddToPlaylistModal()" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <!-- CURRENT TRACK INFO PREVIEW -->
        <div class="bg-zinc-950 p-3 rounded-2xl border border-zinc-800/80 flex items-center gap-3">
            <img id="modalTrackCover" src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=150" class="w-12 h-12 rounded-xl object-cover">
            <div class="min-w-0 flex-1">
                <h4 id="modalTrackTitle" class="text-xs font-bold text-white truncate">Belum Ada Lagu</h4>
                <p id="modalTrackArtist" class="text-[11px] text-zinc-400 truncate">Pilih lagu terlebih dahulu</p>
            </div>
        </div>

        <!-- OPTION 1: EXISTING PLAYLISTS -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Pilih Playlist Yang Ada</h4>
            
            <div id="existingPlaylistsList" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                <div class="bg-zinc-950/80 p-3 rounded-xl flex items-center justify-between border border-zinc-800/60 hover:border-indigo-500/40 transition">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">❤️</span>
                        <div>
                            <h5 class="text-xs font-bold text-white">Favorit Saya</h5>
                            <p class="text-[10px] text-zinc-400">12 lagu</p>
                        </div>
                    </div>
                    <button onclick="addTrackToExistingPlaylist('Favorit Saya')" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold">
                        + Tambahkan
                    </button>
                </div>

                <div class="bg-zinc-950/80 p-3 rounded-xl flex items-center justify-between border border-zinc-800/60 hover:border-indigo-500/40 transition">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">🌙</span>
                        <div>
                            <h5 class="text-xs font-bold text-white">Night Vibe Playlist</h5>
                            <p class="text-[10px] text-zinc-400">8 lagu</p>
                        </div>
                    </div>
                    <button onclick="addTrackToExistingPlaylist('Night Vibe Playlist')" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold">
                        + Tambahkan
                    </button>
                </div>

                <div class="bg-zinc-950/80 p-3 rounded-xl flex items-center justify-between border border-zinc-800/60 hover:border-indigo-500/40 transition">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">⚡</span>
                        <div>
                            <h5 class="text-xs font-bold text-white">Energi & Workout</h5>
                            <p class="text-[10px] text-zinc-400">15 lagu</p>
                        </div>
                    </div>
                    <button onclick="addTrackToExistingPlaylist('Energi & Workout')" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold">
                        + Tambahkan
                    </button>
                </div>
            </div>
        </div>

        <!-- OPTION 2: CREATE NEW PLAYLIST FORM -->
        <div class="pt-4 border-t border-zinc-800 space-y-3">
            <button id="toggleNewPlaylistFormBtn" onclick="toggleNewPlaylistForm()" class="w-full py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-indigo-300 text-xs font-bold border border-zinc-700 transition flex items-center justify-center gap-2">
                <span>➕</span> Buat Playlist Baru
            </button>

            <form id="newPlaylistInlineForm" onsubmit="handleCreateAndAddPlaylist(event)" class="hidden space-y-3 bg-zinc-950 p-4 rounded-2xl border border-zinc-800">
                <div>
                    <label class="block text-[11px] font-semibold text-zinc-300 mb-1">Nama Playlist Baru</label>
                    <input type="text" id="newPlaylistNameInput" required placeholder="Contoh: Lagu Santai Sore" 
                           class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="toggleNewPlaylistForm()" class="px-3 py-1.5 rounded-lg bg-zinc-800 text-zinc-300 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md">
                        Buat & Tambahkan
                    </button>
                </div>
            </form>
        </div>

        <div class="flex justify-end pt-2">
            <button onclick="closeAddToPlaylistModal()" class="px-5 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>

<script>
    // Global Player Engine & Features
    let ytPlayer = null;
    let isYtReady = false;
    let pendingVideoId = null;
    let updateTimer = null;
    let isShuffle = false;
    let isLooping = false; // Single track loop
    let isLiked = false;
    let currentSongData = null;

    window.playlistQueue = [];
    window.currentTrackIndex = -1;
    let isFallbackAttempt = false;
    let isSeeking = false;


    const playPauseBtn = document.getElementById('playPauseBtn');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const shuffleBtn = document.getElementById('shuffleBtn');
    const repeatBtn = document.getElementById('repeatBtn');
    const repeatBadge = document.getElementById('repeatBadge');
    
    const playerLikeBtn = document.getElementById('playerLikeBtn');
    const likeHeartIcon = document.getElementById('likeHeartIcon');
    
    const playerTitle = document.getElementById('playerTitle');
    const playerArtist = document.getElementById('playerArtist');
    const playerCover = document.getElementById('playerCover');
    const seekBar = document.getElementById('seekBar');
    const currentTimeEl = document.getElementById('currentTime');
    const totalDurationEl = document.getElementById('totalDuration');
    const volumeBar = document.getElementById('volumeBar');

    function formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return "0:00";
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
    }

    function showToast(message, icon = '✨') {
        const toast = document.getElementById('toastNotification');
        document.getElementById('toastIcon').textContent = icon;
        document.getElementById('toastMessage').textContent = message;

        toast.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
        }, 3000);
    }

    // Called automatically by YouTube Iframe API
    function onYouTubeIframeAPIReady() {
        ytPlayer = new YT.Player('ytPlayerDiv', {
            height: '200',
            width: '200',
            playerVars: {
                'autoplay': 1,
                'controls': 0,
                'disablekb': 1,
                'fs': 0,
                'modestbranding': 1,
                'rel': 0,
                'origin': window.location.origin,
                'enablejsapi': 1
            },
            events: {
                'onReady': () => { 
                    isYtReady = true; 
                    if (pendingVideoId) {
                        try {
                            ytPlayer.loadVideoById({ videoId: pendingVideoId });
                            ytPlayer.playVideo();
                        } catch(err) {}
                        pendingVideoId = null;
                    }
                },
                'onStateChange': onPlayerStateChange,
                'onError': async (e) => {
                    console.warn('YouTube Player Error Code:', e.data);
                    if (!currentSongData) return;

                    const rawTitle = currentSongData.title || '';
                    const rawArtist = currentSongData.artist || '';
                    const failedId = currentSongData.youtubeId || '';

                    // 1. First Tier Fallback: Try alternative embeddable YouTube track (Topic / Audio / Lyrics version)
                    if (!isFallbackAttempt && (rawTitle || rawArtist)) {
                        isFallbackAttempt = true;
                        showToast('⚡ Mencari versi audio alternatif...', '🔍');

                        const cleanTitle = rawTitle.replace(/Lagu Populer/gi, '').replace(/\([^)]*\)/g, '').replace(/\[[^\]]*\]/g, '').replace(/["']/g, '').trim();
                        const cleanArtist = rawArtist.replace(/\([^)]*\)/g, '').replace(/["']/g, '').trim();

                        try {
                            const query = encodeURIComponent(`${cleanTitle} ${cleanArtist}`);
                            const response = await fetch(`/search?q=${query}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                            });
                            const data = await response.json();
                            if (data && data.results && data.results.length > 0) {
                                const alt = data.results.find(item => item.youtube_id && item.youtube_id !== failedId);
                                if (alt && alt.youtube_id) {
                                    currentSongData.youtubeId = alt.youtube_id;
                                    if (isYtReady && ytPlayer && typeof ytPlayer.loadVideoById === 'function') {
                                        ytPlayer.loadVideoById({ videoId: alt.youtube_id, startSeconds: 0 });
                                        ytPlayer.playVideo();
                                        startProgressTimer();
                                        return;
                                    }
                                }
                            }
                        } catch(err) {
                            console.warn('Search fallback failed:', err);
                        }
                    }

                    // 2. Second Tier Fallback: Direct Audio Stream (Proxy Engine)
                    if (failedId) {
                        showToast('⚡ Memuat Direct Audio Stream...', '🎧');
                        const nativeAudio = document.getElementById('nativeAudioPlayer');
                        if (!nativeAudio) return;

                        if (ytPlayer && typeof ytPlayer.pauseVideo === 'function') {
                            try { ytPlayer.pauseVideo(); } catch(err) {}
                        }

                        try {
                            // Try fetching direct stream URL first (fastest latency)
                            const streamRes = await fetch(`/music/stream/${failedId}`);
                            const streamData = await streamRes.json();
                            if (streamData && streamData.stream_url) {
                                nativeAudio.src = streamData.stream_url;
                            } else {
                                nativeAudio.src = `http://127.0.0.1:8001/audio/${failedId}`;
                            }
                        } catch(err) {
                            nativeAudio.src = `http://127.0.0.1:8001/audio/${failedId}`;
                        }

                        nativeAudio.onerror = function(err) {
                            console.warn('Native audio stream failed:', err);
                            isFallbackAttempt = false;
                            showToast('⚠️ Lagu tidak dapat diputar, memutar lagu berikutnya...', '⏭️');
                            setTimeout(playNextTrack, 1500);
                        };

                        nativeAudio.ontimeupdate = function() {
                            if (isSeeking) return;
                            currentTimeEl.textContent = formatTime(nativeAudio.currentTime);
                            totalDurationEl.textContent = formatTime(nativeAudio.duration);
                            if (nativeAudio.duration > 0 && isFinite(nativeAudio.duration)) {
                                const pct = (nativeAudio.currentTime / nativeAudio.duration) * 100;
                                seekBar.value = pct;
                                updateSeekBarFill(pct);
                            }
                        };

                        nativeAudio.onended = function() {
                            playNextTrack();
                        };

                        try {
                            await nativeAudio.play();
                            playIcon.classList.add('hidden');
                            pauseIcon.classList.remove('hidden');
                            startProgressTimer();
                            return;
                        } catch(err) {
                            console.warn('Native audio play error:', err);
                        }
                    }

                    isFallbackAttempt = false;
                    setTimeout(playNextTrack, 1000);
                }
            }
        });
    }








    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            startProgressTimer();
        } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.CUED) {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            stopProgressTimer();
        } else if (event.data === YT.PlayerState.ENDED) {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            stopProgressTimer();
            if (isLooping) {
                if (ytPlayer && typeof ytPlayer.seekTo === 'function') {
                    ytPlayer.seekTo(0);
                    ytPlayer.playVideo();
                }
            } else {
                playNextTrack();
            }
        }
    }

    function updateSeekBarFill(percent) {
        seekBar.style.setProperty('--seek-progress', percent + '%');
    }

    function updateVolumeBarFill(percent) {
        volumeBar.style.setProperty('--vol-progress', percent + '%');
    }

    // Initialize volume bar fill
    updateVolumeBarFill(volumeBar.value);

    function startProgressTimer() {
        stopProgressTimer();
        updateTimer = setInterval(() => {
            if (isSeeking) return; // Don't update while user is dragging

            // 1. If Native Audio player is active and playing
            const nativeAudio = document.getElementById('nativeAudioPlayer');
            if (nativeAudio && nativeAudio.src && !nativeAudio.paused) {
                const cur = nativeAudio.currentTime || 0;
                const dur = nativeAudio.duration || 0;

                currentTimeEl.textContent = formatTime(cur);
                if (dur > 0 && !isNaN(dur) && isFinite(dur)) {
                    totalDurationEl.textContent = formatTime(dur);
                    const pct = (cur / dur) * 100;
                    seekBar.value = pct;
                    updateSeekBarFill(pct);
                }
                return;
            }

            // 2. If YouTube Player is active
            if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                try {
                    const cur = ytPlayer.getCurrentTime() || 0;
                    const dur = (typeof ytPlayer.getDuration === 'function') ? (ytPlayer.getDuration() || 0) : 0;

                    currentTimeEl.textContent = formatTime(cur);
                    if (dur > 0 && !isNaN(dur) && isFinite(dur)) {
                        totalDurationEl.textContent = formatTime(dur);
                        const pct = (cur / dur) * 100;
                        seekBar.value = pct;
                        updateSeekBarFill(pct);
                    }
                } catch(err) {}
            }
        }, 200);
    }

    function stopProgressTimer() {
        if (updateTimer) clearInterval(updateTimer);
    }

    // High-Resolution Thumbnail Transformer (Fixes low-res / blurry album cover images)
    function getHighResImage(url) {
        if (!url || url.trim() === '') {
            return 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80';
        }
        return url.replace(/=w\d+-h\d+/, '=w800-h800')
                  .replace(/=s\d+/, '=s800')
                  .replace(/=w\d+-h\d+-p/, '=w800-h800-p')
                  .replace(/=w\d+-h\d+-l\d+-rj/, '=w800-h800-l90-rj');
    }

    // Followed Artists Management System (Personalization Algorithm)
    window.followedArtists = JSON.parse(localStorage.getItem('followed_artists') || '[]');

    function isArtistFollowed(artistName) {
        if (!artistName) return false;
        return window.followedArtists.includes(artistName.trim());
    }

    function toggleFollowArtist() {
        if (!currentSongData || !currentSongData.artist) {
            showToast('Pilih lagu terlebih dahulu!', '⚠️');
            return;
        }

        const artist = currentSongData.artist.trim();
        const idx = window.followedArtists.indexOf(artist);

        if (idx === -1) {
            window.followedArtists.push(artist);
            localStorage.setItem('followed_artists', JSON.stringify(window.followedArtists));
            showToast(`✨ Anda sekarang mengikuti ${artist}! Beranda disesuaikan.`, '✅');
        } else {
            window.followedArtists.splice(idx, 1);
            localStorage.setItem('followed_artists', JSON.stringify(window.followedArtists));
            showToast(`Batal mengikuti ${artist}.`, 'ℹ️');
        }

        updateFollowButtons(artist);

        // Notify home page if recommendation refresh is available
        if (typeof window.refreshFollowedArtistSection === 'function') {
            window.refreshFollowedArtistSection();
        }
    }

    function updateFollowButtons(artist) {
        const followBtn = document.getElementById('followArtistBtn');
        const creditBtn = document.getElementById('creditFollowBtn');
        const isFollowed = isArtistFollowed(artist);

        [followBtn, creditBtn].forEach(btn => {
            if (!btn) return;
            if (isFollowed) {
                btn.textContent = '✓ Following';
                btn.className = 'px-4 py-1.5 rounded-full bg-emerald-500 text-zinc-950 font-bold text-xs shadow-md transition';
            } else {
                btn.textContent = 'Follow';
                btn.className = 'px-4 py-1.5 rounded-full border border-zinc-600 hover:border-white text-xs font-bold text-white hover:bg-white hover:text-zinc-950 transition';
            }
        });
    }

    function toggleRightSidebar() {
        const sidebar = document.getElementById('rightSidebar');
        const btn = document.getElementById('toggleRightSidebarBtn');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
            if (btn) {
                btn.classList.toggle('bg-indigo-500/15');
                btn.classList.toggle('bg-zinc-800');
            }
        }
    }

    window.playSongFromBtn = function(btn) {
        if (!btn) return;
        const title = btn.getAttribute('data-title') || '';
        const artist = btn.getAttribute('data-artist') || '';
        const cover = btn.getAttribute('data-cover') || '';
        const ytid = btn.getAttribute('data-youtube-id') || '';
        window.playSong(title, artist, cover, ytid);
    };

    window.playSong = function(title, artist, coverUrl, youtubeId = '') {
        isFallbackAttempt = false;
        currentSongData = { title, artist, coverUrl, youtubeId };

        const nativeAudio = document.getElementById('nativeAudioPlayer');
        if (nativeAudio) {
            nativeAudio.pause();
            nativeAudio.removeAttribute('src');
        }


        const cleanId = (youtubeId || '').trim();
        const highResCover = getHighResImage(coverUrl);

        playerTitle.textContent = title || 'Unknown Title';
        playerArtist.textContent = artist || 'Unknown Artist';
        playerCover.src = highResCover;

        // Update Right Sidebar Details
        const rightCover = document.getElementById('rightCover');
        const rightTitle = document.getElementById('rightTitle');
        const rightArtist = document.getElementById('rightArtist');
        const rightArtistName = document.getElementById('rightArtistName');
        const rightArtistBanner = document.getElementById('rightArtistBanner');
        const rightCreditArtist = document.getElementById('rightCreditArtist');
        const rightArtistBio = document.getElementById('rightArtistBio');

        if (rightCover) rightCover.src = highResCover;
        if (rightTitle) rightTitle.textContent = title || 'Unknown Title';
        if (rightArtist) rightArtist.textContent = artist || 'Unknown Artist';
        if (rightArtistName) rightArtistName.querySelector('span').textContent = artist || 'Unknown Artist';
        if (rightCreditArtist) rightCreditArtist.textContent = artist || 'Unknown Artist';
        if (rightArtistBanner) rightArtistBanner.src = highResCover;
        if (rightArtistBio) {
            rightArtistBio.textContent = `${artist || 'Artis ini'} menyajikan karya musik terbaik dengan aransemen nada memukau yang didengarkan oleh jutaan pencinta musik.`;
        }

        updateFollowButtons(artist);

        // Reset Heart / Like status for new song
        isLiked = false;
        likeHeartIcon.setAttribute('fill', 'none');
        playerLikeBtn.classList.remove('text-rose-500');
        playerLikeBtn.classList.add('text-zinc-500');

        // Add to queue if not present
        const existingIdx = window.playlistQueue.findIndex(item => item.youtubeId === cleanId && cleanId !== '');
        if (existingIdx !== -1) {
            window.currentTrackIndex = existingIdx;
        } else {
            window.playlistQueue.push({ title, artist, coverUrl: highResCover, youtubeId: cleanId });
            window.currentTrackIndex = window.playlistQueue.length - 1;
        }


        currentTimeEl.textContent = "0:00";
        totalDurationEl.textContent = "0:00";
        seekBar.value = 0;
        updateSeekBarFill(0);

        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');

        if (cleanId !== '') {
            if (isYtReady && ytPlayer && typeof ytPlayer.loadVideoById === 'function') {
                try {
                    ytPlayer.loadVideoById({ videoId: cleanId, startSeconds: 0 });
                    ytPlayer.playVideo();
                    startProgressTimer();
                } catch(e) {
                    console.error('Play error:', e);
                }
            } else {
                pendingVideoId = cleanId;
            }
        } else {
            // No youtubeId provided — search for the song via API to find a real videoId
            let cleanQuery = `${title} ${artist}`
                .replace(/Lagu Populer/gi, '')
                .replace(/Populer/gi, '')
                .replace(/\([^)]*\)/g, '')
                .replace(/\[[^\]]*\]/g, '')
                .trim();
            if (!cleanQuery) cleanQuery = artist || title || 'YOASOBI';

            showToast('🔍 Mencari lagu...', '🎵');

            fetch(`/search?q=${encodeURIComponent(cleanQuery)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.results && data.results.length > 0) {
                    const found = data.results.find(item => item.youtube_id);
                    if (found && found.youtube_id) {
                        currentSongData.youtubeId = found.youtube_id;
                        // Update queue entry
                        if (window.playlistQueue[window.currentTrackIndex]) {
                            window.playlistQueue[window.currentTrackIndex].youtubeId = found.youtube_id;
                        }
                        if (found.thumbnail) {
                            const hrCover = getHighResImage(found.thumbnail);
                            playerCover.src = hrCover;
                            const rc = document.getElementById('rightCover');
                            if (rc) rc.src = hrCover;
                        }
                        if (isYtReady && ytPlayer && typeof ytPlayer.loadVideoById === 'function') {
                            ytPlayer.loadVideoById({ videoId: found.youtube_id, startSeconds: 0 });
                            ytPlayer.playVideo();
                            startProgressTimer();
                        } else {
                            pendingVideoId = found.youtube_id;
                        }
                        return;
                    }
                }
                // Fallback direct stream if search yields no direct embed
                showToast('⚡ Memuat Direct Stream...', '🎧');
            })
            .catch(() => {
                showToast('⚠️ Gagal menemukan video, silakan coba lagu lain.', '⚠️');
            });
        }
    };




    function playNextTrack() {
        if (isLooping) {
            if (ytPlayer && typeof ytPlayer.seekTo === 'function') {
                ytPlayer.seekTo(0);
                ytPlayer.playVideo();
                return;
            }
            const nativeAudio = document.getElementById('nativeAudioPlayer');
            if (nativeAudio && nativeAudio.src) {
                nativeAudio.currentTime = 0;
                nativeAudio.play();
                return;
            }
        }

        // If user has multiple songs queued and hasn't reached the end:
        if (window.playlistQueue.length > 1 && window.currentTrackIndex < window.playlistQueue.length - 1) {
            window.currentTrackIndex++;
            const track = window.playlistQueue[window.currentTrackIndex];
            if (track) {
                window.playSong(track.title, track.artist, track.coverUrl, track.youtubeId);
                return;
            }
        }

        // When Loop is OFF -> Pick a RANDOM song by the same/similar artist (NEVER repeat the same song!)
        const currentTitle = currentSongData ? currentSongData.title : '';
        const currentArtist = currentSongData ? currentSongData.artist : 'Artis';

        showToast(`📻 Memutar musik acak berikutnya (${currentArtist})...`, '🎵');

        const songPool = [
            { title: '夜に駆ける (Racing Into The Night)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800', id: 'by4SYYWlhEs' },
            { title: 'アイドル (Idol)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800', id: 'ZRtdQ81jPUQ' },
            { title: '怪物 (Monster)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800', id: 'dy90tA3TT1c' },
            { title: '群青 (Gunjou)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800', id: 'vELh3Q6562U' },
            { title: '廻廻奇譚 (Kaikai Kitan)', artist: 'Eve', cover: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800', id: '1tk1P1MfsCs' },
            { title: 'Kick Back', artist: 'Kenshi Yonezu', cover: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800', id: 'M2cckDmNLMI' },
            { title: '残響散歌 (Zankyosanka)', artist: 'Aimer', cover: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800', id: 'tLQLa6lM36I' },
            { title: 'たぶん (Probably)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800', id: '8iuLXODzL00' },
            { title: '祝福 (The Blessing)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800', id: '3eytpBOkOFA' },
            { title: '勇者 (Yuusha)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800', id: 'OIBODIPC_8Y' },
            { title: 'ハルカ (Haruka)', artist: 'YOASOBI', cover: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800', id: 'VwGZFnQFRGM' },
            { title: 'Pretender', artist: 'Official HIGE DANdism', cover: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800', id: 'TQ8WlA2GXbk' }
        ];

        // Filter out current song so it NEVER loops when loop is OFF!
        const differentSongs = songPool.filter(s => s.title.toLowerCase() !== currentTitle.toLowerCase());
        const chosen = differentSongs[Math.floor(Math.random() * differentSongs.length)] || songPool[0];

        window.playSong(chosen.title, chosen.artist, chosen.cover, chosen.id);
    }



    function playPrevTrack() {
        if (window.playlistQueue.length === 0) return;

        if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function' && ytPlayer.getCurrentTime() > 5) {
            ytPlayer.seekTo(0);
            return;
        }

        window.currentTrackIndex--;
        if (window.currentTrackIndex < 0) {
            window.currentTrackIndex = 0;
        }

        const track = window.playlistQueue[window.currentTrackIndex];
        if (track) {
            window.playSong(track.title, track.artist, track.coverUrl, track.youtubeId);
        }
    }

    // PLAY / PAUSE CONTROLLER (TOGGLE PLAY/PAUSE ICON AND VIDEO STATE)
    playPauseBtn.addEventListener('click', () => {
        const nativeAudio = document.getElementById('nativeAudioPlayer');
        if (nativeAudio && nativeAudio.src && !nativeAudio.paused) {
            nativeAudio.pause();
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            return;
        } else if (nativeAudio && nativeAudio.src && nativeAudio.paused && nativeAudio.currentTime > 0) {
            nativeAudio.play();
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            return;
        }

        if (!ytPlayer) return;

        let isPlaying = false;
        if (typeof ytPlayer.getPlayerState === 'function') {
            const state = ytPlayer.getPlayerState();
            isPlaying = (state === YT.PlayerState.PLAYING || state === 1);
        } else {
            isPlaying = !playIcon.classList.contains('hidden');
        }

        if (isPlaying) {
            if (typeof ytPlayer.pauseVideo === 'function') ytPlayer.pauseVideo();
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            stopProgressTimer();
        } else {
            if (typeof ytPlayer.playVideo === 'function') ytPlayer.playVideo();
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            startProgressTimer();
        }
    });


    // SEEKBAR DRAG / SEEK CONTROLLER
    seekBar.addEventListener('mousedown', () => { isSeeking = true; });
    seekBar.addEventListener('touchstart', () => { isSeeking = true; }, { passive: true });

    seekBar.addEventListener('input', () => {
        isSeeking = true;
        const val = parseFloat(seekBar.value);
        updateSeekBarFill(val);

        const nativeAudio = document.getElementById('nativeAudioPlayer');
        if (nativeAudio && nativeAudio.src && nativeAudio.duration > 0) {
            const targetSec = (val / 100) * nativeAudio.duration;
            currentTimeEl.textContent = formatTime(targetSec);
            return;
        }
        if (ytPlayer && typeof ytPlayer.getDuration === 'function') {
            const dur = ytPlayer.getDuration();
            if (dur > 0) {
                const targetSec = (val / 100) * dur;
                currentTimeEl.textContent = formatTime(targetSec);
            }
        }
    });

    function commitSeek() {
        if (!isSeeking) return;
        const val = parseFloat(seekBar.value);
        updateSeekBarFill(val);

        const nativeAudio = document.getElementById('nativeAudioPlayer');
        if (nativeAudio && nativeAudio.src && nativeAudio.duration > 0) {
            nativeAudio.currentTime = (val / 100) * nativeAudio.duration;
            isSeeking = false;
            return;
        }
        if (ytPlayer && typeof ytPlayer.getDuration === 'function' && typeof ytPlayer.seekTo === 'function') {
            const dur = ytPlayer.getDuration();
            if (dur > 0) {
                const targetSec = (val / 100) * dur;
                ytPlayer.seekTo(targetSec, true);
            }
        }
        isSeeking = false;
    }

    seekBar.addEventListener('change', commitSeek);
    seekBar.addEventListener('mouseup', commitSeek);
    seekBar.addEventListener('touchend', commitSeek);

    // VOLUME CONTROLLER
    volumeBar.addEventListener('input', () => {
        updateVolumeBarFill(volumeBar.value);
        const nativeAudio = document.getElementById('nativeAudioPlayer');
        if (nativeAudio) {
            nativeAudio.volume = volumeBar.value / 100;
        }
        if (ytPlayer && typeof ytPlayer.setVolume === 'function') {
            ytPlayer.setVolume(volumeBar.value);
        }
    });

    // SKIP NEXT & PREVIOUS

    nextBtn.addEventListener('click', playNextTrack);
    prevBtn.addEventListener('click', playPrevTrack);

    // SHUFFLE TOGGLE
    shuffleBtn.addEventListener('click', () => {
        isShuffle = !isShuffle;
        if (isShuffle) {
            shuffleBtn.classList.add('text-indigo-400', 'bg-indigo-500/20');
            shuffleBtn.classList.remove('text-zinc-400');
            showToast('🔀 Putar Acak (Shuffle) Aktif');
        } else {
            shuffleBtn.classList.remove('text-indigo-400', 'bg-indigo-500/20');
            shuffleBtn.classList.add('text-zinc-400');
            showToast('Putar Acak Dimatikan');
        }
    });

    // DIRECT SINGLE-SONG LOOPING TOGGLE
    repeatBtn.addEventListener('click', () => {
        isLooping = !isLooping;
        if (isLooping) {
            repeatBtn.classList.add('text-indigo-400', 'bg-indigo-500/20');
            repeatBtn.classList.remove('text-zinc-400');
            repeatBadge.classList.remove('hidden');
            showToast('🔁 Single Song Loop Aktif', '🔁');
        } else {
            repeatBtn.classList.remove('text-indigo-400', 'bg-indigo-500/20');
            repeatBtn.classList.add('text-zinc-400');
            repeatBadge.classList.add('hidden');
            showToast('Looping Dimatikan', '⏹️');
        }
    });

    // LIKE / FAVORITE HEART BUTTON TOGGLE
    playerLikeBtn.addEventListener('click', () => {
        if (!currentSongData || !currentSongData.title) {
            showToast('Pilih lagu terlebih dahulu!', '⚠️');
            return;
        }

        isLiked = !isLiked;
        if (isLiked) {
            likeHeartIcon.setAttribute('fill', 'currentColor');
            playerLikeBtn.classList.add('text-rose-500');
            playerLikeBtn.classList.remove('text-zinc-500');
            showToast(`❤️ ${currentSongData.title} ditambahkan ke Favorit!`, '❤️');
        } else {
            likeHeartIcon.setAttribute('fill', 'none');
            playerLikeBtn.classList.remove('text-rose-500');
            playerLikeBtn.classList.add('text-zinc-500');
            showToast(`Lagu dihapus dari Favorit.`, '💔');
        }
    });

    // (seekbar and volume handlers are defined above)

    // MODAL ADD TO PLAYLIST LOGIC
    function openAddToPlaylistModal() {
        if (!currentSongData || !currentSongData.title) {
            showToast('Pilih lagu terlebih dahulu!', '⚠️');
            return;
        }

        document.getElementById('modalTrackTitle').textContent = currentSongData.title;
        document.getElementById('modalTrackArtist').textContent = currentSongData.artist;
        if (currentSongData.coverUrl) {
            document.getElementById('modalTrackCover').src = currentSongData.coverUrl;
        }

        const modal = document.getElementById('addToPlaylistModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAddToPlaylistModal() {
        const modal = document.getElementById('addToPlaylistModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('newPlaylistInlineForm').classList.add('hidden');
    }

    function addTrackToExistingPlaylist(playlistName) {
        if (!currentSongData) return;
        showToast(`✨ ${currentSongData.title} ditambahkan ke ${playlistName}!`, '📁');
        closeAddToPlaylistModal();
    }

    function toggleNewPlaylistForm() {
        const form = document.getElementById('newPlaylistInlineForm');
        form.classList.toggle('hidden');
    }

    function handleCreateAndAddPlaylist(e) {
        e.preventDefault();
        const newName = document.getElementById('newPlaylistNameInput').value;
        if (!newName || !currentSongData) return;

        // Append to existing playlists list in modal
        const list = document.getElementById('existingPlaylistsList');
        const item = document.createElement('div');
        item.className = 'bg-zinc-950/80 p-3 rounded-xl flex items-center justify-between border border-zinc-800/60 hover:border-indigo-500/40 transition';
        item.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="text-lg">🎵</span>
                <div>
                    <h5 class="text-xs font-bold text-white">${newName}</h5>
                    <p class="text-[10px] text-zinc-400">1 lagu</p>
                </div>
            </div>
            <button onclick="addTrackToExistingPlaylist('${newName}')" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold">
                + Tambahkan
            </button>
        `;
        list.appendChild(item);

        showToast(`✨ Playlist "${newName}" dibuat & lagu ditambahkan!`, '🎉');
        closeAddToPlaylistModal();
    }

    // =========================================================================
    // SPA PERSISTENT PLAYBACK NAVIGATION ENGINE
    // Prevents full page reloads on internal link clicks so music never stops!
    // =========================================================================
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.hasAttribute('target') || link.hasAttribute('download')) {
            return;
        }

        // Check if link is an internal site URL
        const targetUrl = new URL(href, window.location.origin);
        if (targetUrl.origin === window.location.origin) {
            e.preventDefault();
            spaNavigateTo(targetUrl.href);
        }
    });

    // Handle form submissions (like search bar or room join) via SPA fetch
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.id === 'newPlaylistInlineForm' || form.hasAttribute('onsubmit')) return;

        const formAction = new URL(form.action || window.location.href, window.location.origin);
        if (formAction.origin === window.location.origin && form.method.toUpperCase() === 'GET') {
            e.preventDefault();
            const formData = new FormData(form);
            const params = new URLSearchParams();
            for (const [key, val] of formData.entries()) {
                if (val) params.set(key, val);
            }
            const fullUrl = formAction.pathname + '?' + params.toString();
            spaNavigateTo(fullUrl);
        }
    });

    window.addEventListener('popstate', function() {
        spaLoadContent(window.location.href, false);
    });

    function spaNavigateTo(url) {
        spaLoadContent(url, true);
    }

    function spaLoadContent(url, pushState = true) {
        const progressBar = document.getElementById('spaProgressBar');
        const targetContainer = document.getElementById('pageContentContainer');

        if (progressBar) progressBar.style.width = '60%';
        if (targetContainer) targetContainer.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newContent = doc.getElementById('pageContentContainer');

            if (newContent && targetContainer) {
                targetContainer.innerHTML = newContent.innerHTML;
            } else {
                window.location.href = url;
                return;
            }

            if (doc.title) {
                document.title = doc.title;
            }

            if (pushState) {
                history.pushState(null, '', url);
            }

            // Sync topbar input with query string
            const topbarInput = document.querySelector('header input[name="q"]');
            const searchUrlParams = new URLSearchParams(new URL(url, window.location.origin).search);
            if (topbarInput) {
                topbarInput.value = searchUrlParams.get('q') || '';
            }

            // Update sidebar active link highlights
            spaUpdateSidebarActive(url);

            // Execute any embedded scripts in the newly loaded content
            if (targetContainer) {
                const scripts = targetContainer.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }

            // Reset scroll position of main content container
            const mainEl = document.querySelector('main');
            if (mainEl) mainEl.scrollTop = 0;

            if (progressBar) progressBar.style.width = '100%';
            if (targetContainer) targetContainer.style.opacity = '1';
            setTimeout(() => { if (progressBar) progressBar.style.width = '0%'; }, 300);
        })
        .catch(err => {
            console.error('SPA Navigation fallback:', err);
            window.location.href = url;
        });
    }


    function spaUpdateSidebarActive(url) {
        const currentPath = new URL(url, window.location.origin).pathname;
        document.querySelectorAll('aside nav a').forEach(a => {
            const href = a.getAttribute('href');
            if (!href) return;
            const linkPath = new URL(href, window.location.origin).pathname;
            
            if (linkPath === currentPath) {
                a.className = 'flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 bg-indigo-600/15 text-indigo-400 border border-indigo-500/30 shadow-inner';
            } else {
                a.className = 'flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200 text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50';
            }
        });
    }

    // Flash session toast triggers
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            showToast("{{ addslashes(session('success')) }}", '✅');
        @elseif(session('error'))
            showToast("{{ addslashes(session('error')) }}", '⚠️');
        @elseif(session('info'))
            showToast("{{ addslashes(session('info')) }}", 'ℹ️');
        @endif
    });
</script>

@stack('scripts')
</body>
</html>