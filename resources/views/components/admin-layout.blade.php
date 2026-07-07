@props([
    'title' => 'Dashboard',
    'subtitle' => 'Selamat datang!',
    'pendingBookings' => 0,
])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - Admin Panel</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/SportFields.png') }}">

    {{-- Font Awesome (kit milikmu) --}}
    <script src="https://kit.fontawesome.com/80f227685e.js" crossorigin="anonymous"></script>

    {{-- Vite: Tailwind + Alpine --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Slot tambahan untuk <style>/CSS khusus halaman (opsional) --}}
    {{ $styles ?? '' }}
</head>

<body class="font-sans bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">

    {{-- Overlay saat sidebar terbuka di mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/30 z-30 md:hidden"></div>

    {{-- Sidebar --}}
    <x-admin-sidebar :pending-bookings="$pendingBookings" />

    {{-- Main Content --}}
    <div class="md:ml-64 transition-[margin] duration-300">

        {{-- Top Navigation --}}
        <nav class="bg-white border-b border-gray-200 px-6 py-3 sticky top-0 z-20">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg text-gray-700 text-2xl hover:bg-gray-100 md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $title }}</h2>
                        <p class="text-sm text-gray-500">{{ $subtitle }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Search --}}
                    <div class="relative hidden md:block">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Cari..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm outline-none transition focus:border-green-600 focus:ring-[3px] focus:ring-green-600/10">
                    </div>

                    {{-- Notifications --}}
                    <button class="relative p-2 rounded-lg text-gray-600 text-xl hover:bg-gray-100">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- Profile --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center text-white font-semibold">
                            {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AD' }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Page Content --}}
        <main class="min-h-[calc(100vh-65px)]">
            <div class="p-4 md:p-6 animate-[fadeIn_0.4s_ease-out]">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Slot untuk script khusus halaman (opsional) --}}
    {{ $scripts ?? '' }}
</body>

</html>