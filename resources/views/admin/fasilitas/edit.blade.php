<x-admin-layout title="Edit Fasilitas" subtitle="Ubah data fasilitas">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Edit Fasilitas</h3>
        <p class="text-sm text-gray-500">Perbarui nama fasilitas yang sudah tersimpan.</p>
    </div>

    @include('admin.fasilitas._form', [
        'action' => route('admin.fasilitas.update', $fasilitas),
        'method' => 'PUT',
        'fasilitas' => $fasilitas,
    ])
</x-admin-layout>