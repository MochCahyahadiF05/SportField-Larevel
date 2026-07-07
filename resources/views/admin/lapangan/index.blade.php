<x-admin-layout title="Manajemen Lapangan" subtitle="Kelola semua lapangan olahraga">
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-green-600">Manajemen Lapangan</p>
            <h3 class="mt-2 text-3xl font-bold text-gray-900">Kelola semua lapangan olahraga</h3>
            <p class="mt-2 text-sm text-gray-500">Daftar lapangan, jenis olahraga, status, fasilitas, dan harga per jam.</p>
        </div>

        <a href="{{ route('admin.lapangan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
            <i class="fas fa-plus"></i>
            Tambah Lapangan
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Lapangan</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-4xl font-bold text-gray-900">{{ $totalLapangan }}</p>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
            <p class="mt-3 text-xs text-green-600">Data lapangan aktif di sistem</p>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Lapangan Aktif</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-4xl font-bold text-green-600">{{ $lapanganAktif }}</p>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600">
                    <i class="fas fa-circle-check text-xl"></i>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500">Siap digunakan</p>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Dalam Perbaikan</p>
            <div class="mt-3 flex items-end justify-between">
                <p class="text-4xl font-bold text-orange-600">{{ $dalamPerbaikan }}</p>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-orange-500">
                    <i class="fas fa-triangle-exclamation text-xl"></i>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500">Estimasi perbaikan berjalan</p>
        </div>
    </div>

    <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Jenis Olahraga</label>
                <select class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                    <option>Semua Jenis</option>
                    @foreach ($jenisOlahragaOptions as $jenisOlahraga)
                        <option>{{ $jenisOlahraga->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
                <select class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                    <option>Semua Status</option>
                    <option>Tersedia</option>
                    <option>Perbaikan</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Fasilitas</label>
                <select class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                    <option>Semua Fasilitas</option>
                    @foreach ($fasilitasOptions as $fasilitas)
                        <option>{{ $fasilitas->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Harga (per jam)</label>
                <select class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                    <option>Semua Harga</option>
                    <option>Di bawah Rp 100.000</option>
                    <option>Rp 100.000 - Rp 250.000</option>
                    <option>Di atas Rp 250.000</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-4 flex items-center justify-between gap-4">
        <h4 class="text-xl font-bold text-gray-900">Daftar Lapangan</h4>
        <span class="text-sm text-gray-500">{{ $lapangans->count() }} data ditemukan</span>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($lapangans as $lapangan)
            <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="relative h-56 overflow-hidden bg-gray-100">
                    <img
                        src="{{ $lapangan->gambar ? asset('storage/' . $lapangan->gambar) : asset('assets/img/Logo.png') }}"
                        alt="{{ $lapangan->nama_lapangan }}"
                        class="h-full w-full object-cover"
                    >
                    <span class="absolute right-4 top-4 rounded-full px-3 py-1 text-xs font-semibold {{ $lapangan->status === 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ $lapangan->status === 'tersedia' ? 'Aktif' : 'Perbaikan' }}
                    </span>
                </div>

                <div class="space-y-3 p-5">
                    <div>
                        <h5 class="text-lg font-bold text-gray-900">{{ $lapangan->nama_lapangan }}</h5>
                        <p class="text-sm text-gray-500">{{ $lapangan->jenisOlahraga?->name ?? '-' }}</p>
                    </div>

                    <p class="text-sm text-gray-600">{{ $lapangan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>

                    <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                        <span class="rounded-full bg-gray-100 px-3 py-1">Jam {{ $lapangan->jam_buka }} - {{ $lapangan->jam_tutup }}</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</span>
                    </div>

                    <div class="pt-1 text-sm text-gray-600">
                        <span class="font-semibold text-gray-900">Fasilitas:</span>
                        {{ $lapangan->fasilitas->pluck('name')->implode(', ') ?: '-' }}
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <a href="{{ route('admin.lapangan.edit', $lapangan) }}" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-green-600 hover:text-green-700">Edit</a>
                        <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus lapangan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-2xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
                Belum ada data lapangan.
            </div>
        @endforelse
    </div>
</x-admin-layout>