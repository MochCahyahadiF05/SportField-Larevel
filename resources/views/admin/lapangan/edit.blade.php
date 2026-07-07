<x-admin-layout title="Edit Lapangan" subtitle="Perbarui data lapangan">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Edit Lapangan</h3>
            <p class="text-sm text-gray-500">Ubah informasi lapangan yang sudah tersimpan.</p>
        </div>
    </div>

    @include('admin.lapangan._form', [
        'action' => route('admin.lapangan.update', $lapangan),
        'method' => 'PUT',
        'lapangan' => $lapangan,
        'jenisOlahragas' => $jenisOlahragas,
        'fasilitas' => $fasilitas,
    ])
</x-admin-layout>