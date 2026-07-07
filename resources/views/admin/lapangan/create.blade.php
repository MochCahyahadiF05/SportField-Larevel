<x-admin-layout title="Tambah Lapangan" subtitle="Tambahkan lapangan baru ke sistem">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Tambah Lapangan</h3>
            <p class="text-sm text-gray-500">Isi data lapangan, jenis olahraga, dan fasilitas yang tersedia.</p>
        </div>
    </div>

    @include('admin.lapangan._form', [
        'action' => route('admin.lapangan.store'),
        'method' => 'POST',
        'lapangan' => null,
        'jenisOlahragas' => $jenisOlahragas,
        'fasilitas' => $fasilitas,
    ])
</x-admin-layout>