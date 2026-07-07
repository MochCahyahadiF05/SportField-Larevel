<form method="POST" action="{{ $action }}" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Jenis Olahraga</label>
        <input type="text" name="name" value="{{ old('name', $jenisOlahraga->name ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
            <i class="fas fa-save"></i>
            Simpan
        </button>
        <a href="{{ route('admin.jenis-olahraga.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:border-gray-300 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>