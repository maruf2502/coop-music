@extends('layouts.music')

@section('title', 'VibeMusic - Pustaka Saya')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- HEADER BANNER -->
    <div class="bg-gradient-to-r from-zinc-900 via-indigo-950/40 to-zinc-900 border border-zinc-800/80 p-8 rounded-3xl shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center gap-3">
                <span>📚</span> Pustaka Musik Kamu
            </h1>
            <p class="text-zinc-400 text-sm">Kelola lagu favorit, playlist buatanmu, dan riwayat musik yang pernah kamu dengarkan.</p>
        </div>

        <button onclick="openModal('createPlaylistModal')" 
                class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-lg shadow-indigo-600/30 flex items-center gap-2 self-start sm:self-auto">
            <span>➕</span> Buat Playlist Baru
        </button>
    </div>

    <!-- LIBRARY NAVIGATION TABS -->
    <div class="flex items-center gap-2 border-b border-zinc-800/80 pb-4">
        <button onclick="switchTab('likedTab')" id="btn-likedTab" 
                class="tab-btn px-5 py-2.5 rounded-xl font-bold text-xs transition bg-indigo-600 text-white shadow-md">
            ❤️ Lagu Yang Disukai
        </button>
        <button onclick="switchTab('playlistTab')" id="btn-playlistTab" 
                class="tab-btn px-5 py-2.5 rounded-xl font-bold text-xs transition text-zinc-400 hover:text-white hover:bg-zinc-800/60">
            📁 Playlist Saya
        </button>
        <button onclick="switchTab('historyTab')" id="btn-historyTab" 
                class="tab-btn px-5 py-2.5 rounded-xl font-bold text-xs transition text-zinc-400 hover:text-white hover:bg-zinc-800/60">
            🕒 Riwayat Mendengarkan
        </button>
    </div>

    <!-- TAB 1: LAGU YANG DISUKAI -->
    <div id="likedTab" class="tab-content space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Lagu Favorit (Lagu Yang Disukai)</h2>
            <span class="text-xs text-zinc-400">3 Lagu</span>
        </div>

        <div class="space-y-3">
            @php
                $liked = [
                    ['title' => '夜に駆ける (Racing Into The Night)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'duration' => '04:22', 'ytid' => 'by4SYYWlhEs'],
                    ['title' => 'アイドル (Idol)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj', 'duration' => '03:34', 'ytid' => 'ZRtdQ81jPUQ'],
                    ['title' => '怪物 (Monster)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'duration' => '03:26', 'ytid' => 'dy90tA3TT1c'],
                ];
            @endphp

            @foreach($liked as $lk)
            <div onclick="playSong('{{ addslashes($lk['title']) }}', '{{ addslashes($lk['artist']) }}', '{{ $lk['cover'] }}', '{{ $lk['ytid'] }}')" 
                 class="group bg-zinc-900/40 hover:bg-zinc-800/70 border border-zinc-800/80 p-3.5 rounded-2xl flex items-center gap-4 transition cursor-pointer">
                <img src="{{ $lk['cover'] }}" class="w-12 h-12 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-white truncate group-hover:text-indigo-400 transition">{{ $lk['title'] }}</h3>
                    <p class="text-xs text-zinc-400 truncate">{{ $lk['artist'] }}</p>
                </div>
                <span class="text-xs font-mono text-zinc-500 mr-2">{{ $lk['duration'] }}</span>
                <button class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-600/30 hover:scale-105 transition">
                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 2: PLAYLIST -->
    <div id="playlistTab" class="tab-content hidden space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Playlist Kamu</h2>
            <button onclick="openModal('createPlaylistModal')" class="text-xs font-bold text-indigo-400 hover:underline">+ Playlist Baru</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" id="playlistContainer">
            @php
                $playlistsDemo = [
                    ['name' => 'Favorit Saya', 'count' => '12 lagu', 'icon' => '❤️', 'bg' => 'from-rose-600 to-pink-700', 'title' => '夜に駆ける', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'ytid' => 'by4SYYWlhEs'],
                    ['name' => 'Night Vibe Playlist', 'count' => '8 lagu', 'icon' => '🌙', 'bg' => 'from-indigo-600 to-purple-800', 'title' => 'たぶん (Probably)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'ytid' => '8iuLXODzL00'],
                    ['name' => 'Energi & Workout', 'count' => '15 lagu', 'icon' => '⚡', 'bg' => 'from-amber-500 to-orange-600', 'title' => 'Kick Back', 'artist' => 'Kenshi Yonezu', 'cover' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=150', 'ytid' => 'M2cckDmNLMI'],
                ];
            @endphp

            @foreach($playlistsDemo as $pl)
            <div onclick="playSong('{{ $pl['title'] }}', '{{ $pl['artist'] }}', '{{ $pl['cover'] }}', '{{ $pl['ytid'] }}')" 
                 class="group bg-zinc-900/50 hover:bg-zinc-800/80 border border-zinc-800/80 p-5 rounded-3xl cursor-pointer transition flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $pl['bg'] }} text-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                    {{ $pl['icon'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-bold text-white group-hover:text-indigo-400 transition truncate">{{ $pl['name'] }}</h3>
                    <p class="text-xs text-zinc-400">{{ $pl['count'] }} • Putar ▶</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 3: RIWAYAT MENDENGARKAN -->
    <div id="historyTab" class="tab-content hidden space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Riwayat Terakhir Diputar</h2>
            <span class="text-xs text-zinc-400">Hari ini</span>
        </div>

        <div class="space-y-3">
            @php
                $historiesDemo = [
                    ['title' => '夜に駆ける (Racing Into The Night)', 'artist' => 'YOASOBI', 'time' => '10 menit yang lalu', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'ytid' => 'by4SYYWlhEs'],
                    ['title' => 'アイドル (Idol)', 'artist' => 'YOASOBI', 'time' => '35 menit yang lalu', 'cover' => 'https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj', 'ytid' => 'ZRtdQ81jPUQ'],
                    ['title' => '怪物 (Monster)', 'artist' => 'YOASOBI', 'time' => '2 jam yang lalu', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'ytid' => 'dy90tA3TT1c'],
                    ['title' => 'ハルカ (Haruka)', 'artist' => 'YOASOBI', 'time' => 'Kemarin', 'cover' => 'https://yt3.googleusercontent.com/1MHUMxR-99XhjSFYzgOewhG32w91DIW7RTW90EEIh1CkPcMD2R5SNoajkt-24Hq6Zs2lPcgtMHdQZMxL1A=w120-h120-l90-rj', 'ytid' => 'VwGZFnQFRGM'],
                ];
            @endphp

            @foreach($historiesDemo as $hs)
            <div onclick="playSong('{{ $hs['title'] }}', '{{ $hs['artist'] }}', '{{ $hs['cover'] }}', '{{ $hs['ytid'] }}')" 
                 class="bg-zinc-900/40 hover:bg-zinc-800/70 border border-zinc-800/80 p-3.5 rounded-2xl flex items-center justify-between transition cursor-pointer group">
                <div class="flex items-center gap-4 min-w-0">
                    <img src="{{ $hs['cover'] }}" class="w-11 h-11 rounded-xl object-cover">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-white truncate group-hover:text-indigo-400 transition">{{ $hs['title'] }}</h4>
                        <p class="text-xs text-zinc-400 truncate">{{ $hs['artist'] }}</p>
                    </div>
                </div>
                <span class="text-xs text-zinc-500 font-medium">{{ $hs['time'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

<!-- CREATE PLAYLIST MODAL -->
<div id="createPlaylistModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>📁</span> Buat Playlist Baru
            </h3>
            <button onclick="closeModal('createPlaylistModal')" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <form onsubmit="handleCreatePlaylist(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Playlist</label>
                <input type="text" id="playlistName" required placeholder="Contoh: Lagu Galau Malam Hari" 
                       class="w-full bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Deskripsi Singkat</label>
                <textarea id="playlistDesc" placeholder="Deskripsi playlist..." rows="2" 
                          class="w-full bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeModal('createPlaylistModal')" class="px-4 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-500 shadow-md">
                    Buat Playlist
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-md');
            btn.classList.add('text-zinc-400', 'hover:text-white');
        });

        document.getElementById(tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.add('bg-indigo-600', 'text-white', 'shadow-md');
        activeBtn.classList.remove('text-zinc-400');
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function handleCreatePlaylist(e) {
        e.preventDefault();
        const name = document.getElementById('playlistName').value;

        const container = document.getElementById('playlistContainer');
        const card = document.createElement('div');
        card.className = 'group bg-zinc-900/50 hover:bg-zinc-800/80 border border-zinc-800/80 p-5 rounded-3xl cursor-pointer transition flex items-center gap-4';
        card.innerHTML = `
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-pink-600 text-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                🎵
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-bold text-white group-hover:text-indigo-400 transition truncate">${name}</h3>
                <p class="text-xs text-zinc-400">0 lagu</p>
            </div>
        `;
        container.appendChild(card);
        closeModal('createPlaylistModal');
        switchTab('playlistTab');
    }
</script>
@endpush
@endsection
