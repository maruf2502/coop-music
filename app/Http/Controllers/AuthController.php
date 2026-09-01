<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('music.home');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login manual.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('music.home'))
                ->with('success', 'Selamat datang kembali, '.Auth::user()->name.'!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan halaman registrasi.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('music.home');
        }

        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan login.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('music.home')
            ->with('success', 'Akun berhasil dibuat! Selamat mendengarkan musik di VibeMusic, '.$user->name.' 🎉');
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('music.home')
            ->with('info', 'Anda telah berhasil keluar (logout).');
    }

    /**
     * Redirect ke halaman otentikasi Google.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Integrasi Google OAuth belum dikonfigurasi di .env (GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET).');
        }

        try {
            /** @var GoogleProvider $driver */
            $driver = Socialite::driver('google');
            $driver->setHttpClient(new Client([
                'verify' => false,
                'timeout' => 15,
            ]));

            return $driver->stateless()
                ->with(['prompt' => 'select_account'])
                ->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal menghubungkan ke layanan Google: '.$e->getMessage());
        }
    }

    /**
     * Menerima callback dari Google OAuth dan login/register user.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            /** @var GoogleProvider $driver */
            $driver = Socialite::driver('google');
            $driver->setHttpClient(new Client([
                'verify' => false,
                'timeout' => 15,
            ]));

            $googleUser = $driver->stateless()->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->with('error', 'Otentikasi dengan Google gagal: '.$e->getMessage());
        }

        if (empty($googleUser->getEmail())) {
            return redirect()->route('login')->with('error', 'Tidak dapat mengambil alamat email dari akun Google Anda.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', strtolower($googleUser->getEmail()))
            ->first();

        if ($user) {
            // Update data Google jika belum ada
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?? $user->avatar,
            ]);
        } else {
            // Buat user baru dari data Google
            $user = User::create([
                'name' => $googleUser->getName() ?? 'Pengguna Google',
                'email' => strtolower($googleUser->getEmail()),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null, // Login dengan Google tidak perlu password
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('music.home'))
            ->with('success', 'Berhasil masuk dengan akun Google! Halo, '.$user->name.' 👋');
    }
}
