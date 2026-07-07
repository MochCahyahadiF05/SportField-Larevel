@php
    $isEdit = isset($lapangan);
    $selectedFasilitas = old('fasilitas_ids', $isEdit ? $lapangan->fasilitas->pluck('id')->all() : []);
    $jamBuka = old('jam_buka', $isEdit ? \Illuminate\Support\Carbon::parse($lapangan->jam_buka)->format('H:i') : '06:00');
    $jamTutup = old('jam_tutup', $isEdit ? \Illuminate\Support\Carbon::parse($lapangan->jam_tutup)->format('H:i') : '23:00');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Lapangan</label>
            <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
            @error('nama_lapangan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Jenis Olahraga</label>
            <select name="jenis_olahraga_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                <option value="">Pilih jenis olahraga</option>
                @foreach ($jenisOlahragas as $jenisOlahraga)
                    <option value="{{ $jenisOlahraga->id }}" @selected((string) old('jenis_olahraga_id', $lapangan->jenis_olahraga_id ?? '') === (string) $jenisOlahraga->id)>
                        {{ $jenisOlahraga->name }}
                    </option>
                @endforeach
            </select>
            @error('jenis_olahraga_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Harga Per Jam</label>
            <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
            @error('harga_per_jam') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Jam Buka</label>
            <input type="time" name="jam_buka" value="{{ $jamBuka }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
            @error('jam_buka') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Jam Tutup</label>
            <input type="time" name="jam_tutup" value="{{ $jamTutup }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
            <p class="mt-2 text-xs text-gray-500">Kalau tidak diubah, sistem tetap bisa menyimpan nilai default.</p>
            @error('jam_tutup') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
            <select name="status" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                <option value="tersedia" @selected(old('status', $lapangan->status ?? 'tersedia') === 'tersedia')>Tersedia</option>
                <option value="perbaikan" @selected(old('status', $lapangan->status ?? '') === 'perbaikan')>Perbaikan</option>
            </select>
            @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Gambar Utama</label>
            <input type="file" name="gambar" accept="image/*" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-green-700 focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
            <p class="mt-2 text-xs text-gray-500">Upload file gambar, bukan URL. Format JPG, PNG, atau WEBP.</p>
            @if ($isEdit && !empty($lapangan->gambar))
                <div class="mt-3 overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                    <img src="{{ asset('storage/' . $lapangan->gambar) }}" alt="Preview gambar lapangan" class="h-40 w-full object-cover">
                </div>
            @endif
            @error('gambar') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">{{ old('deskripsi', $lapangan->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Fasilitas</label>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($fasilitas as $fasilitasItem)
                <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700">
                    <input type="checkbox" name="fasilitas_ids[]" value="{{ $fasilitasItem->id }}" @checked(in_array($fasilitasItem->id, $selectedFasilitas)) class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600">
                    <span>{{ $fasilitasItem->name }}</span>
                </label>
            @endforeach
        </div>
        @error('fasilitas_ids') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
            <i class="fas fa-save"></i>
            Simpan
        </button>
        <a href="{{ route('admin.lapangan.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:border-gray-300 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>