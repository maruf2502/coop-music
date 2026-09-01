@extends('layouts.music')

@section('title', 'VibeMusic - Room ' . ($room->name ?? 'Detail'))

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- ROOM HEADER CARD -->
    <div class="bg-gradient-to-r from-indigo-950 via-zinc-900 to-purple-950 border border-indigo-500/30 p-8 rounded-3xl shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <span class="px-3 py-1 text-xs font-mono font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-lg">
                    KODE ROOM: <span id="roomCodeText" class="text-white">{{ $room->code ?? 'AB12CD' }}</span>
                </span>
                <span class="px-2.5 py-0.5 text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Host Live
                </span>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-2">{{ $room->name ?? 'Listening Room' }}</h1>
            <p class="text-xs text-zinc-400">Host: <span class="text-indigo-400 font-semibold">{{ $room->host->name ?? 'Host' }}</span> • Kontrol: <span class="text-zinc-200 capitalize font-medium">{{ str_replace('_', ' ', $room->control_mode ?? 'host_only') }}</span></p>
        </div>

        <!-- ACTION BUTTONS: ADD FRIEND, ADD MUSIC, EDIT (HOST), HAPUS (HOST) -->
        <div class="flex items-center gap-2.5 flex-wrap z-10">
            <!-- 👥 ADD FRIEND -->
            <button onclick="openModal('addFriendModal')" 
                    class="px-4 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 font-bold text-xs border border-zinc-700 transition flex items-center gap-1.5 shadow-md">
                <span>👥</span> Tambah Teman
            </button>

            <!-- 🎵 ADD MUSIC -->
            <button onclick="openModal('addMusicModal')" 
                    class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30">
                <span>🎵</span> Tambah Lagu
            </button>

            <!-- HOST-ONLY BUTTONS (EDIT & HAPUS) -->
            <button onclick="openModal('editRoomModal')" 
                    class="px-3.5 py-2.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 font-bold text-xs transition flex items-center gap-1" title="Edit Room Settings">
                <span>✏️</span> Edit Room
            </button>

            <button onclick="openModal('deleteRoomModal')" 
                    class="px-3.5 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/30 font-bold text-xs transition flex items-center gap-1" title="Hapus Room">
                <span>🗑️</span> Hapus
            </button>
        </div>
    </div>

    <!-- MAIN GRID: CURRENTLY PLAYING + ROOM QUEUE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- CURRENTLY PLAYING PLAYER IN ROOM -->
        <div class="lg:col-span-1 bg-zinc-900/50 border border-zinc-800/80 p-6 rounded-3xl space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-400">Diputar Di Room Ini</h2>
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                </div>

                <div class="aspect-square rounded-2xl overflow-hidden bg-zinc-950 mb-4 shadow-xl border border-zinc-800 relative group">
                    <img id="roomCurrentCover" 
                         src="https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj" 
                         alt="Current Song" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="playSong('夜に駆ける (Racing Into The Night)', 'YOASOBI', 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'by4SYYWlhEs')" 
                                class="w-14 h-14 rounded-full bg-white text-zinc-950 flex items-center justify-center shadow-xl font-bold hover:scale-110 transition-transform">
                            ▶
                        </button>
                    </div>
                </div>

                <div class="text-center">
                    <h3 id="roomCurrentTitle" class="text-lg font-extrabold text-white mb-1">夜に駆ける</h3>
                    <p id="roomCurrentArtist" class="text-xs text-zinc-400">YOASOBI</p>
                </div>
            </div>

            <!-- ANGGOTA ONLINE IN ROOM -->
            <div class="border-t border-zinc-800/60 pt-4">
                <h4 class="text-xs font-semibold text-zinc-400 mb-2">Anggota Room (Online)</h4>
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs border border-white/20" title="Host">
                        {{ substr($room->host->name ?? 'H', 0, 1) }}
                    </div>
                    <div class="w-8 h-8 rounded-full bg-pink-600 text-white font-bold flex items-center justify-center text-xs border border-white/20" title="Anggota 1">
                        S
                    </div>
                    <div class="w-8 h-8 rounded-full bg-purple-600 text-white font-bold flex items-center justify-center text-xs border border-white/20" title="Anggota 2">
                        B
                    </div>
                </div>
            </div>
        </div>

        <!-- ROOM QUEUE LIST -->
        <div class="lg:col-span-2 bg-zinc-900/50 border border-zinc-800/80 p-6 rounded-3xl space-y-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2 class="text-xl font-bold text-white">Queue Antrean Lagu</h2>
                    <p class="text-xs text-zinc-400">Lagu yang akan diputar selanjutnya di Room ini</p>
                </div>

                <button onclick="openModal('addMusicModal')" 
                        class="px-3.5 py-1.5 rounded-xl bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 border border-indigo-500/30 text-xs font-bold transition flex items-center gap-1">
                    <span>➕</span> Tambah Lagu
                </button>
            </div>

            <!-- QUEUE ITEMS -->
            <div id="queueListContainer" class="space-y-3">
                @php
                    $queueItems = [
                        ['id' => 1, 'pos' => 1, 'title' => '夜に駆ける (Racing Into The Night)', 'artist' => 'YOASOBI', 'added_by' => 'Host (Maruf)', 'cover' => 'https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj', 'status' => 'Sedang Diputar', 'ytid' => 'by4SYYWlhEs'],
                        ['id' => 2, 'pos' => 2, 'title' => 'アイドル (Idol)', 'artist' => 'YOASOBI', 'added_by' => 'Sarah', 'cover' => 'https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj', 'status' => 'Antrean berikutnya', 'ytid' => 'ZRtdQ81jPUQ'],
                        ['id' => 3, 'pos' => 3, 'title' => '怪物 (Monster)', 'artist' => 'YOASOBI', 'added_by' => 'Budi', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj', 'status' => 'Dalam antrean', 'ytid' => 'dy90tA3TT1c'],
                    ];
                @endphp

                @foreach($queueItems as $q)
                <div onclick="playSong('{{ addslashes($q['title']) }}', '{{ addslashes($q['artist']) }}', '{{ $q['cover'] }}', '{{ $q['ytid'] }}')"
                     class="group bg-zinc-950/60 hover:bg-zinc-800/60 border border-zinc-800/80 p-3.5 rounded-2xl flex items-center gap-4 transition cursor-pointer">
                    <span class="text-sm font-extrabold text-zinc-500 w-6 text-center">#{{ $q['pos'] }}</span>
                    <img src="{{ $q['cover'] }}" class="w-12 h-12 rounded-xl object-cover">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-white truncate group-hover:text-indigo-400 transition">{{ $q['title'] }}</h4>
                        <p class="text-xs text-zinc-400 truncate">{{ $q['artist'] }} • <span class="text-zinc-500">Ditambahkan oleh {{ $q['added_by'] }}</span></p>
                    </div>
                    @if($q['pos'] === 1)
                    <span class="px-2.5 py-1 text-[10px] font-bold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-md">PLAYING</span>
                    @else
                    <button onclick="event.stopPropagation(); removeQueueItem({{ $q['id'] }}, this)" 
                            class="text-zinc-500 hover:text-rose-400 p-2 rounded-lg hover:bg-rose-500/10 transition" title="Hapus dari Queue">
                        🗑️
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

<!-- 👥 MODAL 1: ADD FRIEND (TAMBAH TEMAN) -->
<div id="addFriendModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>👥</span> Undang Teman ke Room
            </h3>
            <button onclick="closeModal('addFriendModal')" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-400 mb-1.5">Kode Room Anda</label>
                <div class="flex gap-2">
                    <input type="text" readonly value="{{ $room->code ?? 'AB12CD' }}" id="shareCodeInput" 
                           class="flex-1 bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-indigo-400 font-mono font-bold">
                    <button onclick="copyToClipboard('shareCodeInput')" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl">
                        Salin Kode
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-400 mb-1.5">Link Undangan Direct</label>
                <div class="flex gap-2">
                    <input type="text" readonly value="{{ url('/rooms/' . ($room->id ?? '1')) }}" id="shareLinkInput" 
                           class="flex-1 bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-xs text-zinc-300 font-mono truncate">
                    <button onclick="copyToClipboard('shareLinkInput')" class="bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl border border-zinc-700">
                        Salin Link
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-800 flex justify-end">
            <button onclick="closeModal('addFriendModal')" class="px-5 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- 🎵 MODAL 2: ADD MUSIC (TAMBAH LAGU KE QUEUE) -->
<div id="addMusicModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>🎵</span> Cari & Tambah Lagu ke Room
            </h3>
            <button onclick="closeModal('addMusicModal')" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <div class="space-y-4">
            <div class="flex gap-2">
                <input type="text" id="modalMusicQuery" placeholder="Cari judul lagu atau penyanyi..." 
                       class="flex-1 bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-indigo-500">
                <button onclick="searchModalMusic()" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md">
                    Cari
                </button>
            </div>

            <!-- RESULT LIST -->
            <div id="modalMusicResults" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                @php
                    $searchDemos = [
                        ['title' => '怪物 (Monster)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj'],
                        ['title' => '祝福 (The Blessing)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/yZS5cGvyeDKPkCI5cmlD3p_O_CCjE4N6msFalbOEhuRytLta0QgzjPbJbzlh2KGzmEiG6sLafrzEf1Vx=w120-h120-l90-rj'],
                        ['title' => 'ハルカ (Haruka)', 'artist' => 'YOASOBI', 'cover' => 'https://yt3.googleusercontent.com/1MHUMxR-99XhjSFYzgOewhG32w91DIW7RTW90EEIh1CkPcMD2R5SNoajkt-24Hq6Zs2lPcgtMHdQZMxL1A=w120-h120-l90-rj'],
                    ];
                @endphp
                @foreach($searchDemos as $sd)
                <div class="bg-zinc-950 p-2.5 rounded-xl flex items-center justify-between border border-zinc-800/80">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $sd['cover'] }}" class="w-10 h-10 rounded-lg object-cover">
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-white truncate">{{ $sd['title'] }}</h4>
                            <p class="text-[11px] text-zinc-400 truncate">{{ $sd['artist'] }}</p>
                        </div>
                    </div>
                    <button onclick="addSongToQueue('{{ addslashes($sd['title']) }}', '{{ addslashes($sd['artist']) }}', '{{ $sd['cover'] }}')" 
                            class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-sm">
                        + Antrean
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-800 flex justify-end">
            <button onclick="closeModal('addMusicModal')" class="px-5 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                Selesai
            </button>
        </div>
    </div>
</div>

<!-- ✏️ MODAL 3: EDIT ROOM (HOST) -->
<div id="editRoomModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>✏️</span> Edit Pengaturan Room
            </h3>
            <button onclick="closeModal('editRoomModal')" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <form onsubmit="handleEditRoom(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Room</label>
                <input type="text" id="editRoomName" value="{{ $room->name ?? 'Listening Room' }}" required 
                       class="w-full bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Mode Kontrol Player</label>
                <select id="editControlMode" class="w-full bg-zinc-950 border border-zinc-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="host_only">Hanya Host yang Boleh Mengontrol</option>
                    <option value="everyone">Semua Anggota Boleh Mengontrol</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeModal('editRoomModal')" class="px-4 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-500 shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🗑️ MODAL 4: DELETE ROOM (HOST) -->
<div id="deleteRoomModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-bold text-rose-400 flex items-center gap-2">
                <span>🗑️</span> Hapus Room Ini?
            </h3>
            <button onclick="closeModal('deleteRoomModal')" class="text-zinc-500 hover:text-white text-xl">✕</button>
        </div>

        <p class="text-xs text-zinc-300 leading-relaxed">
            Apakah Anda yakin ingin menutup dan menghapus room ini? Semua anggota akan dikeluarkan dan queue lagu akan dibersihkan.
        </p>

        <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
            <button type="button" onclick="closeModal('deleteRoomModal')" class="px-4 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-bold hover:bg-zinc-700">
                Batal
            </button>
            <a href="{{ route('music.rooms') }}" class="px-5 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-500 shadow-md">
                Ya, Hapus Room
            </a>
        </div>
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

    function copyToClipboard(inputId) {
        const input = document.getElementById(inputId);
        input.select();
        document.execCommand('copy');
        alert('Teks berhasil disalin!');
    }

    function addSongToQueue(title, artist, cover) {
        const container = document.getElementById('queueListContainer');
        const count = container.children.length + 1;

        const newItem = document.createElement('div');
        newItem.className = 'group bg-zinc-950/60 hover:bg-zinc-800/60 border border-zinc-800/80 p-3.5 rounded-2xl flex items-center gap-4 transition';
        newItem.innerHTML = `
            <span class="text-sm font-extrabold text-zinc-500 w-6 text-center">#${count}</span>
            <img src="${cover}" class="w-12 h-12 rounded-xl object-cover">
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-bold text-white truncate group-hover:text-indigo-400 transition">${title}</h4>
                <p class="text-xs text-zinc-400 truncate">${artist} • <span class="text-zinc-500">Ditambahkan oleh Anda</span></p>
            </div>
            <button onclick="removeQueueItem(0, this)" class="text-zinc-500 hover:text-rose-400 p-2 rounded-lg hover:bg-rose-500/10 transition" title="Hapus dari Queue">
                🗑️
            </button>
        `;

        container.appendChild(newItem);
        closeModal('addMusicModal');
    }

    function removeQueueItem(id, btn) {
        const item = btn.closest('.group');
        if (item) item.remove();
    }

    function handleEditRoom(e) {
        e.preventDefault();
        const newName = document.getElementById('editRoomName').value;
        document.querySelector('h1').textContent = newName;
        closeModal('editRoomModal');
        alert('Data room berhasil diperbarui!');
    }
</script>
@endpush
@endsection
