@props(['pendingBookings' => 0])

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200 transition-transform duration-300 md:translate-x-0">

    <div class="h-full py-4 px-3 overflow-y-auto relative">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-8 px-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center shrink-0">
                <img src="{{ asset('assets/img/Logo.png') }}" alt="SportField logo" class="w-10 h-10 object-contain"
                     onerror="this.remove()">
            </div>
            <div>
                <h1 class="text-xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent leading-tight">
                    SportField
                </h1>
                <p class="text-xs text-gray-500">Admin Panel</p>
            </div>
        </div>

        {{-- Menu Items --}}
        {{-- CATATAN: sesuaikan nama route() di bawah dengan route asli kamu (routes/web.php) --}}
        <ul class="list-none mb-16">
            {{-- <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.dashboard')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-home w-5 text-xl {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li> --}}
            <li class="mb-2">
                <a href="{{ route('admin.booking.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.booking.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-calendar w-5 text-xl {{ request()->routeIs('admin.booking.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Booking</span>
                    @if ($pendingBookings > 0)
                        <span class="ml-auto bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $pendingBookings }}
                        </span>
                    @endif
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.lapangan.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.lapangan.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-building w-5 text-xl {{ request()->routeIs('admin.lapangan.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Lapangan</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.jenis-olahraga.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.jenis-olahraga.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-list w-5 text-xl {{ request()->routeIs('admin.jenis-olahraga.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Jenis Olahraga</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.fasilitas.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.fasilitas.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-tools w-5 text-xl {{ request()->routeIs('admin.fasilitas.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Fasilitas</span>
                </a>
            </li>
            {{-- <li class="mb-2">
                <a href="{{ route('admin.pelanggan.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.pelanggan.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-users w-5 text-xl {{ request()->routeIs('admin.pelanggan.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Pelanggan</span>
                </a>
            </li> --}}
            <li class="mb-2">
                <a href="{{ route('admin.pembayaran.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.pembayaran.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-credit-card w-5 text-xl {{ request()->routeIs('admin.pembayaran.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Pembayaran</span>
                </a>
            </li>
            {{-- <li class="mb-2">
                <a href="{{ route('admin.laporan.index') }}"
                   class="flex items-center p-3 rounded-lg font-medium transition relative
                          {{ request()->routeIs('admin.laporan.*')
                                ? 'bg-gradient-to-r from-green-600 to-green-500 text-white'
                                : 'text-gray-700 hover:bg-green-600/10 hover:translate-x-1' }}">
                    <i class="fas fa-chart-bar w-5 text-xl {{ request()->routeIs('admin.laporan.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ml-3">Laporan</span>
                </a>
            </li> --}}
        </ul>

        {{-- Logout --}}
        <div class="absolute bottom-4 left-3 right-3 border-t border-gray-200 pt-4">
            <form method="POST" action="{{ route('logout') }}"
                  onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit"
                    class="w-full flex items-center p-3 rounded-lg font-medium text-gray-700 transition hover:bg-red-500/10 group">
                    <i class="fas fa-sign-out-alt w-5 text-xl text-gray-500 group-hover:text-red-500"></i>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>

    </div>
</aside>