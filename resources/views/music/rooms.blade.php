@extends('layouts.music')

@section('title', 'VibeMusic - Listening Rooms')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- HEADER BANNER -->
    <div class="bg-gradient-to-r from-indigo-950 via-purple-950 to-zinc-900 border border-indigo-500/20 p-8 rounded-3xl shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 text-[10px] font-bold bg-pink-500/20 text-pink-400 border border-pink-500/30 rounded-full">REALTIME ROOM</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center gap-3">
                <span>🎧</span> Listening Rooms
            </h1>
            <p class="text-zinc-400 text-sm max-w-xl">Dengarkan musik bareng teman dalam satu ruangan secara bersamaan. Buat room baru atau gabung dengan kode room.</p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            <button onclick="openModal('createRoomModal')" 
                    class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition shadow-lg shadow-indigo-600/30 border border-indigo-400/30 flex items-center gap-2">
                <span>➕</span> Buat Room Baru
            </button>
        </div>
    </div>

    <!-- JOIN WITH CODE FORM -->
    <div class="bg-zinc-900/50 border border-zinc-800/80 p-6 rounded-3xl">
        <h2 class="text-base font-bold text-white mb-3">🔑 Punya Kode Room?</h2>
        <form action="{{ route('music.rooms') }}" method="GET" class="flex gap-3 max-w-md">
            <input type="text" name="code" value="{{ request('code') }}" placeholder="Masukkan Kode Room (ex: AB12CD)" 
                   class="flex-1 bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-500 uppercase font-mono focus:outline-none focus:border-indigo-500">
            <button type="submit" class="bg-zinc-800 hover:bg-zinc-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm border border-zinc-700 transition">
                Gabung
            </button>
        </form>
    </div>

    <!-- ROOMS LIST -->
    <div>
        <h2 class="text-xl font-bold text-white mb-4">Room Aktif Saat Ini</h2>

        @if(count($rooms) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($rooms as $room)
            <div class="group bg-zinc-900/60 hover:bg-zinc-800/80 border border-zinc-800/80 p-5 rounded-3xl transition-all duration-300 hover:border-indigo-500/40 hover:-translate-y-1 shadow-xl flex flex-col justify-between h-48">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 text-xs font-mono font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-lg">
                            KODE: {{ $room->code }}
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-emerald-400 font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition mb-1">{{ $room->name }}</h3>
                    <p class="text-xs text-zinc-400">Host: <span class="text-zinc-200 font-semibold">{{ $room->host->name ?? 'User Host' }}</span></p>
                </div>

                <div class="flex items-center justify-between border-t border-zinc-800/60 pt-3">
                    <div class="text-xs text-zinc-500 flex items-center gap-2">
                        <span>👥 {{ $room->members ? count($room->members) : 1 }} anggota</span>
                    </div>
                    <a href="{{ route('music.room.detail', ['room' => $room->id]) }}" 
                       class="px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition">
                        Masuk Room →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- DEMO ACTIVE ROOM CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                $demoRooms = [
                    ['id' => 1, 'code' => 'YOASOBI', 'name' => 'YOASOBI Fanstation 🌸', 'host' => 'Maruf', 'members' => 5],
                    ['id' => 2, 'code' => 'LOFIVB', 'name' => 'Lofi Chill Night ☕', 'host' => 'Sarah', 'members' => 3],
                    ['id' => 3, 'code' => 'ROCK99', 'name' => 'Rock Legends Live 🎸', 'host' => 'Budi', 'members' => 8],
                ];
            @endphp
            @foreach($demoRooms as $drm)
            <div class="group bg-zinc-900/60 hover:bg-zinc-800/80 border border-zinc-800/80 p-5 rounded-3xl transition-all duration-300 hover:border-indigo-500/40 hover:-translate-y-1 shadow-xl flex flex-col justify-between h-48">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 text-xs font-mono font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-lg">
                            KODE: {{ $drm['code'] }}
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-emerald-400 font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition mb-1">{{ $drm['name'] }}</h3>
                    <p class="text-xs text-zinc-400">Host: <span class="text-zinc-200 font-semibold">{{ $drm['host'] }}</span></p>
                </div>

                <div class="flex items-center justify-between border-t border-zinc-800/60 pt-3">
                    <div class="text-xs text-zinc-500 flex items-center gap-2">
                        <span>👥 {{ $drm['members'] }} anggota online</span>
                    </div>
                    <a href="{{ route('music.room.detail', ['room' => $drm['id']]) }}" 
                       class="px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition">
                        Masuk Room →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

<!-- CREATE ROOM MODAL -->
<div id="createRoomModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>🎧</span> Buat Room Musik Baru
            </h3>
            <button onclick="closeModal('createRoomModal')" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <form action="{{ route('music.rooms') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Room</label>
                <input type="text" name="name" required placeholder="Contoh: Chill Lofi Night" 
                       class="w-full bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Mode Kontrol Player</label>
                <select name="control_mode" class="w-full bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="host_only">Hanya Host yang Boleh Mengontrol Player</option>
                    <option value="everyone">Semua Anggota Boleh Mengontrol</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="allow_add_song" checked value="1" class="rounded bg-zinc-950 border-zinc-700 text-indigo-600 focus:ring-0">
                    <span class="text-xs text-zinc-300">Izinkan anggota menambahkan lagu ke Queue</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="allow_chat" checked value="1" class="rounded bg-zinc-950 border-zinc-700 text-indigo-600 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan Chat Room</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeModal('createRoomModal')" class="px-4 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-500 shadow-md">
                    Buat Room sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }
</script>
@endpush
@endsection
