<x-admin-layout title="Jenis Olahraga" subtitle="Kelola kategori olahraga untuk setiap lapangan">
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Jenis Olahraga</h3>
            <p class="text-sm text-gray-500">Daftar kategori olahraga yang digunakan pada lapangan.</p>
        </div>
        <a href="{{ route('admin.jenis-olahraga.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
            <i class="fas fa-plus"></i>
            Tambah Jenis
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Jumlah Lapangan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($jenisOlahragas as $jenisOlahraga)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $jenisOlahraga->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $jenisOlahraga->lapangan_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.jenis-olahraga.edit', $jenisOlahraga) }}" class="rounded-lg border border-gray-200 px-3 py-2 text-gray-700 transition hover:border-green-600 hover:text-green-700">Edit</a>
                                <form action="{{ route('admin.jenis-olahraga.destroy', $jenisOlahraga) }}" method="POST" onsubmit="return confirm('Hapus jenis olahraga ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-red-600 transition hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">Belum ada data jenis olahraga.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>