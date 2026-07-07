@php
    use Illuminate\Support\Carbon;
@endphp

<x-admin-layout title="Pembayaran" subtitle="Lihat hasil pembayaran Midtrans dari customer">
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-green-600">Manajemen Pembayaran</p>
            <h3 class="mt-2 text-3xl font-bold text-gray-900">Hasil pembayaran customer</h3>
            <p class="mt-2 text-sm text-gray-500">Menampilkan status pembayaran, metode transaksi, dan waktu pembayaran yang diterima dari Midtrans.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="mt-3 text-4xl font-bold text-amber-600">{{ $pendingPembayaran }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Berhasil</p>
            <p class="mt-3 text-4xl font-bold text-green-600">{{ $berhasilPembayaran }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Gagal</p>
            <p class="mt-3 text-4xl font-bold text-red-600">{{ $gagalPembayaran }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-6 py-4">
            <div>
                <h4 class="text-xl font-bold text-gray-900">Daftar Pembayaran</h4>
                <p class="text-sm text-gray-500">Semua transaksi yang dibuat customer melalui Midtrans.</p>
            </div>
            <span class="text-sm text-gray-500">{{ $pembayarans->count() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Lapangan</th>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Bayar</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($pembayarans as $pembayaran)
                        @php
                            $badge = match ($pembayaran->status) {
                                'pending' => 'bg-amber-100 text-amber-700',
                                'berhasil' => 'bg-green-100 text-green-700',
                                'gagal' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $pembayaran->booking?->user?->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $pembayaran->booking?->user?->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $pembayaran->booking?->lapangan?->nama_lapangan ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $pembayaran->booking?->lapangan?->jenisOlahraga?->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $pembayaran->midtrans_order_id ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $pembayaran->payment_type ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst($pembayaran->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $pembayaran->paid_at ? Carbon::parse($pembayaran->paid_at)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>