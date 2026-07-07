<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SportField - Register</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/SportFields.png') }}">

    {{-- Vite: load Tailwind (resources/css/app.css) & Alpine (resources/js/app.js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden font-sans bg-gradient-to-br from-green-50 via-white to-green-100">

    <div class="min-h-screen flex items-center justify-center px-4 py-8">
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
                                <span class="bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">Bergabung</span>
                                <br>
                                <span class="text-gray-900">Bersama Kami</span>
                            </h2>

                            <p class="text-lg text-gray-600 leading-relaxed mb-8">
                                Daftar sekarang dan nikmati kemudahan booking lapangan olahraga terbaik.
                            </p>

                            {{-- Feature cards --}}
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-4 bg-white/95 backdrop-blur-xl border border-white/30 p-4 rounded-xl">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-2xl shrink-0">✓</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-0.5">Gratis Selamanya</h4>
                                        <p class="text-sm text-gray-600">Tanpa biaya pendaftaran</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white/95 backdrop-blur-xl border border-white/30 p-4 rounded-xl">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-2xl shrink-0">💳</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-0.5">Pembayaran Mudah</h4>
                                        <p class="text-sm text-gray-600">Berbagai metode pembayaran</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white/95 backdrop-blur-xl border border-white/30 p-4 rounded-xl">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-2xl shrink-0">🎁</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-0.5">Bonus Member Baru</h4>
                                        <p class="text-sm text-gray-600">Dapatkan welcome bonus</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ RIGHT SIDE - REGISTER FORM ============ --}}
                <div>
                    <div class="bg-white/95 backdrop-blur-xl border border-white/30 rounded-3xl shadow-2xl p-6 md:p-8 max-w-[520px] mx-auto relative z-10
                                max-h-[calc(100vh-64px)] overflow-y-auto
                                [&::-webkit-scrollbar]:w-2
                                [&::-webkit-scrollbar-track]:bg-black/5 [&::-webkit-scrollbar-track]:rounded-full
                                [&::-webkit-scrollbar-thumb]:bg-green-600/40 [&::-webkit-scrollbar-thumb]:rounded-full
                                hover:[&::-webkit-scrollbar-thumb]:bg-green-600/60">

                        {{-- Logo for mobile --}}
                        <div class="flex lg:hidden items-center justify-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center shrink-0">
                                <span class="text-white text-xl font-bold">SF</span>
                            </div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">
                                SportField
                            </h1>
                        </div>

                        <div class="text-center mb-6">
                            <h3 class="text-2xl md:text-[28px] font-bold text-gray-900 mb-1">Daftar Akun</h3>
                            <p class="text-gray-600 text-sm">Buat akun baru dan mulai booking</p>
                        </div>

                        {{-- General error --}}
                        @if ($errors->any())
                            <div class="bg-red-100 text-red-600 px-4 py-3 rounded-xl mb-5 text-sm">
                                {{ __('Mohon periksa kembali data yang Anda masukkan.') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-[14px]"
                              x-data="{ showPassword: false, showConfirm: false }">
                            @csrf

                            {{-- Nama Lengkap --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="name" class="text-xs font-semibold text-gray-700">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                        placeholder="John Doe"
                                        class="w-full pl-[38px] pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            {{-- Email --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="email" class="text-xs font-semibold text-gray-700">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username"
                                        placeholder="john@example.com"
                                        class="w-full pl-[38px] pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            {{-- Nomor Telepon (kolom custom, lihat catatan) --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="phone" class="text-xs font-semibold text-gray-700">Nomor Telepon</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        placeholder="08xxxxxxxxxx"
                                        class="w-full pl-[38px] pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                </div>
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>

                            {{-- Password --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="password" class="text-xs font-semibold text-gray-700">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required autocomplete="new-password"
                                        placeholder="••••••••"
                                        class="w-full pl-[38px] pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="password_confirmation" class="text-xs font-semibold text-gray-700">Konfirmasi Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                        placeholder="••••••••"
                                        class="w-full pl-[38px] pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                            </div>

                            {{-- Terms checkbox (client-side only, lihat catatan) --}}
                            <div class="flex items-start gap-2 pt-1">
                                <input type="checkbox" id="terms" required
                                    class="w-4 h-4 mt-0.5 rounded border-gray-300 text-green-600 focus:ring-green-600 cursor-pointer shrink-0">
                                <label for="terms" class="text-xs text-gray-600 leading-relaxed">
                                    Saya setuju dengan <a href="#" class="text-green-600 font-semibold hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-green-600 font-semibold hover:underline">Kebijakan Privasi</a>
                                </label>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                class="w-full mt-1 bg-gradient-to-r from-green-600 to-green-500 text-white py-3 rounded-xl font-semibold text-sm transition hover:shadow-lg hover:scale-[1.02]">
                                Daftar Sekarang
                            </button>

                            @if (Route::has('login'))
                                <div class="text-center pt-2">
                                    <p class="text-xs text-gray-600">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:underline ml-1">Login di sini</a>
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