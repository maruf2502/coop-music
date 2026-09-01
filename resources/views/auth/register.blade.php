<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - VibeMusic</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-full flex items-center justify-center p-4 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- BACKGROUND GLOW EFFECTS -->
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-indigo-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-10 left-10 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-md my-8">
        
        <!-- LOGO & BRANDING -->
        <div class="text-center mb-8">
            <a href="{{ route('music.home') }}" class="inline-flex items-center gap-3 group mb-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 .895-2 3-2 3 .895 3 2zm12 0c0 1.105-1.343 2-3 2s-3-.895-3-2 .895-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-2xl font-extrabold bg-gradient-to-r from-white via-zinc-200 to-zinc-400 bg-clip-text text-transparent">VibeMusic</span>
                    <span class="block text-[10px] uppercase tracking-widest text-indigo-400 font-bold">Streaming & Room Live</span>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-white tracking-tight mt-2">Buat Akun Baru</h1>
            <p class="text-zinc-400 text-xs mt-1">Bergabunglah dan mulai simpan lagu serta buat Room musik</p>
        </div>

        <!-- AUTH CARD CONTAINER -->
        <div class="bg-zinc-900/80 backdrop-blur-2xl border border-zinc-800/80 rounded-3xl p-8 shadow-2xl space-y-6">

            <!-- ALERT MESSAGES IF ANY -->
            @if(session('error'))
            <div class="p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-semibold flex items-center gap-2.5">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <!-- 1. GOOGLE REGISTER BUTTON -->
            <div>
                <a href="{{ route('auth.google') }}" 
                   class="w-full flex items-center justify-center gap-3 bg-zinc-950 hover:bg-zinc-800/90 text-white font-bold text-xs py-3.5 px-4 rounded-2xl border border-zinc-700/80 hover:border-indigo-500/50 transition-all duration-200 shadow-md group">
                    <svg class="w-4 h-4 flex-shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Daftar Cepat dengan Google</span>
                </a>
            </div>

            <!-- DIVIDER -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-zinc-800 w-full"></div>
                <span class="bg-zinc-900 px-3 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider relative">atau daftar dengan email</span>
            </div>

            <!-- 2. MANUAL REGISTER FORM -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- NAME FIELD -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-semibold text-zinc-300">Nama Lengkap</label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="Nama Anda"
                               class="w-full bg-zinc-950 border @error('name') border-rose-500 @else border-zinc-700/80 @enderror rounded-2xl pl-11 pr-4 py-3 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner">
                        <span class="absolute left-4 top-3.5 text-zinc-500 text-xs">👤</span>
                    </div>
                    @error('name')
                    <p class="text-rose-400 text-[11px] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EMAIL FIELD -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-zinc-300">Alamat Email</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="nama@email.com"
                               class="w-full bg-zinc-950 border @error('email') border-rose-500 @else border-zinc-700/80 @enderror rounded-2xl pl-11 pr-4 py-3 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner">
                        <span class="absolute left-4 top-3.5 text-zinc-500 text-xs">✉️</span>
                    </div>
                    @error('email')
                    <p class="text-rose-400 text-[11px] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD FIELD -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-semibold text-zinc-300">Kata Sandi (Min. 6 Karakter)</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               placeholder="••••••••"
                               class="w-full bg-zinc-950 border @error('password') border-rose-500 @else border-zinc-700/80 @enderror rounded-2xl pl-11 pr-4 py-3 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner">
                        <span class="absolute left-4 top-3.5 text-zinc-500 text-xs">🔒</span>
                    </div>
                    @error('password')
                    <p class="text-rose-400 text-[11px] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD CONFIRMATION FIELD -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-300">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="••••••••"
                               class="w-full bg-zinc-950 border border-zinc-700/80 rounded-2xl pl-11 pr-4 py-3 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner">
                        <span class="absolute left-4 top-3.5 text-zinc-500 text-xs">🔐</span>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition-all duration-200 shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-95 pt-2">
                    <span>Daftar Sekarang</span>
                    <span>→</span>
                </button>
            </form>

        </div>

        <!-- FOOTER NAV -->
        <div class="text-center mt-6 space-y-2">
            <p class="text-xs text-zinc-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition underline underline-offset-4">
                    Masuk di sini
                </a>
            </p>
            <div>
                <a href="{{ route('music.home') }}" class="text-xs text-zinc-500 hover:text-zinc-300 transition">
                    ← Kembali ke Beranda Musik
                </a>
            </div>
        </div>

    </div>

</body>
</html>
