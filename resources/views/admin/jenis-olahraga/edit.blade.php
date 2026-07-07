<x-admin-layout title="Edit Jenis Olahraga" subtitle="Ubah data kategori olahraga">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Edit Jenis Olahraga</h3>
        <p class="text-sm text-gray-500">Perbarui nama kategori yang sudah tersimpan.</p>
    </div>

    @include('admin.jenis-olahraga._form', [
        'action' => route('admin.jenis-olahraga.update', $jenisOlahraga),
        'method' => 'PUT',
        'jenisOlahraga' => $jenisOlahraga,
    ])
</x-admin-layout>