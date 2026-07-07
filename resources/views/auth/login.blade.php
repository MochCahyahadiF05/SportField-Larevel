<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SportField - Login</title>

    {{-- Favicon (sesuaikan path asset kamu, mis. public/assets/img/SportFields.png) --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/SportFields.png') }}">

    {{-- Vite: load Tailwind (resources/css/app.css) & Alpine (resources/js/app.js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden font-sans bg-gradient-to-br from-green-50 via-white to-green-100">

    <div class="h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-[1280px] mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- ============ LEFT SIDE - ILLUSTRATION ============ --}}
                <div class="hidden lg:flex flex-col justify-center">
                    <div class="relative">
                        {{-- Blur circles --}}
                        <div class="absolute w-72 h-72 rounded-full blur-[80px] -top-20 -left-20 bg-green-600/20"></div>
                        <div class="absolute w-72 h-72 rounded-full blur-[80px] -bottom-20 -right-20 bg-green-400/20"></div>

                        <div class="relative z-10">
                            {{-- Logo --}}
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center shrink-0">
                                    <span class="text-white text-2xl font-bold">SF</span>
                                </div>
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">
                                    SportField
                                </h1>
                            </div>

                            {{-- Title --}}
                            <h2 class="text-5xl font-bold leading-tight mb-6">
                                <span class="bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">Selamat Datang</span>
                                <br>
                                <span class="text-gray-900">Kembali!</span>
                            </h2>

                            <p class="text-lg text-gray-600 leading-relaxed mb-8">
                                Login untuk mengakses akun Anda dan mulai booking lapangan olahraga favorit.
                            </p>

                            {{-- Feature cards --}}
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-4 bg-white/95 backdrop-blur-xl border border-white/30 p-4 rounded-xl">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-2xl shrink-0">⚡</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-0.5">Booking Cepat</h4>
                                        <p class="text-sm text-gray-600">Proses booking hanya 30 detik</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white/95 backdrop-blur-xl border border-white/30 p-4 rounded-xl">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-2xl shrink-0">🏆</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-0.5">Lapangan Premium</h4>
                                        <p class="text-sm text-gray-600">Fasilitas terbaik untuk Anda</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white/95 backdrop-blur-xl border border-white/30 p-4 rounded-xl">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-2xl shrink-0">🎁</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-0.5">Reward Points</h4>
                                        <p class="text-sm text-gray-600">Dapatkan poin setiap booking</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ RIGHT SIDE - LOGIN FORM ============ --}}
                <div>
                    <div class="bg-white/95 backdrop-blur-xl border border-white/30 rounded-3xl shadow-2xl p-7 md:p-9 max-w-[480px] mx-auto relative z-10">

                        {{-- Logo for mobile --}}
                        <div class="flex lg:hidden items-center justify-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center shrink-0">
                                <span class="text-white text-2xl font-bold">SF</span>
                            </div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">
                                SportField
                            </h1>
                        </div>

                        <div class="text-center mb-7">
                            <h3 class="text-[28px] font-bold text-gray-900 mb-1.5">Login</h3>
                            <p class="text-gray-600 text-[15px]">Masuk ke akun Anda</p>
                        </div>

                        {{-- Session Status (mis. setelah register/reset password) --}}
                        @if (session('status'))
                            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-5 text-sm">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- General error (mis. email/password salah) --}}
                        @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                            <div class="bg-red-100 text-red-600 px-4 py-3 rounded-xl mb-5 text-sm">
                                {{ __('Terjadi kesalahan. Silakan coba lagi.') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-[18px]" x-data="{ showPassword: false }">
                            @csrf

                            {{-- Email --}}
                            <div class="flex flex-col gap-2">
                                <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                        placeholder="email@example.com"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            {{-- Password --}}
                            <div class="flex flex-col gap-2">
                                <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required autocomplete="current-password"
                                        placeholder="••••••••"
                                        class="w-full pl-12 pr-12 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            {{-- Remember me & Forgot password --}}
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="flex items-center cursor-pointer">
                                    <input id="remember_me" type="checkbox" name="remember"
                                        class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-600 cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-green-600 hover:underline">
                                        {{ __('Lupa password?') }}
                                    </a>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-600 to-green-500 text-white py-3 rounded-xl font-semibold text-[15px] transition hover:shadow-lg hover:scale-[1.02]">
                                Login Sekarang
                            </button>

                            @if (Route::has('register'))
                                <div class="text-center mt-1">
                                    <p class="text-sm text-gray-600">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:underline ml-1">Daftar Sekarang</a>
                                    </p>
                                </div>
                            @endif
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>