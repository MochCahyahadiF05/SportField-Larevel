@php
    use Illuminate\Support\Carbon;
@endphp

<x-admin-layout title="Booking" subtitle="Kelola konfirmasi, pembatalan, dan penyelesaian booking" :pending-bookings="$pendingBookings">
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-green-600">Manajemen Booking</p>
            <h3 class="mt-2 text-3xl font-bold text-gray-900">Kelola status booking pelanggan</h3>
            <p class="mt-2 text-sm text-gray-500">Konfirmasi booking yang masuk, batalkan yang bermasalah, dan tandai selesai saat permainan berakhir.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="mt-3 text-4xl font-bold text-amber-600">{{ $pendingBookings }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Confirmed</p>
            <p class="mt-3 text-4xl font-bold text-blue-600">{{ $confirmedBookings }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="mt-3 text-4xl font-bold text-green-600">{{ $completedBookings }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Cancelled</p>
            <p class="mt-3 text-4xl font-bold text-red-600">{{ $cancelledBookings }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-6 py-4">
            <div>
                <h4 class="text-xl font-bold text-gray-900">Daftar Booking</h4>
                <p class="text-sm text-gray-500">Semua booking yang masuk ke sistem.</p>
            </div>
            <span class="text-sm text-gray-500">{{ $bookings->count() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Lapangan</th>
                        <th class="px-6 py-4">Jadwal</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($bookings as $booking)
                        @php
                            $statusBadge = match ($booking->status) {
                                'pending' => 'bg-amber-100 text-amber-700',
                                'confirmed' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $booking->user?->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user?->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $booking->lapangan?->nama_lapangan ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->lapangan?->jenisOlahraga?->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <div>{{ $booking->tanggal ? Carbon::parse($booking->tanggal)->format('d/m/Y') : '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $booking->jam_mulai ? Carbon::parse($booking->jam_mulai)->format('H:i') : '-' }}
                                    -
                                    {{ $booking->jam_selesai ? Carbon::parse($booking->jam_selesai)->format('H:i') : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusBadge }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    @if ($booking->status === 'pending')
                                        <form action="{{ route('admin.booking.confirm', $booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                                                Konfirmasi Mulai
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batal booking ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-xl border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                Batal
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'confirmed')
                                        <form action="{{ route('admin.booking.complete', $booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-xl bg-green-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                                Selesai
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batal booking ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-xl border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                Batal
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Tidak ada aksi</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada data booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>