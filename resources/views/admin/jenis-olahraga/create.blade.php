<x-admin-layout title="Tambah Jenis Olahraga" subtitle="Tambahkan kategori olahraga baru">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Tambah Jenis Olahraga</h3>
        <p class="text-sm text-gray-500">Gunakan nama yang singkat dan mudah dipahami.</p>
    </div>

    @include('admin.jenis-olahraga._form', [
        'action' => route('admin.jenis-olahraga.store'),
        'method' => 'POST',
        'jenisOlahraga' => null,
    ])
</x-admin-layout>