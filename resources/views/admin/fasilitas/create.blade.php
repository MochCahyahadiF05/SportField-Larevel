<x-admin-layout title="Tambah Fasilitas" subtitle="Tambahkan fasilitas baru untuk lapangan">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Tambah Fasilitas</h3>
        <p class="text-sm text-gray-500">Gunakan nama fasilitas yang jelas dan singkat.</p>
    </div>

    @include('admin.fasilitas._form', [
        'action' => route('admin.fasilitas.store'),
        'method' => 'POST',
        'fasilitas' => null,
    ])
</x-admin-layout>