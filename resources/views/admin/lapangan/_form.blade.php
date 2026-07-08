@php
    $isEdit = isset($lapangan);
    $selectedFasilitasIds = old('fasilitas_ids', $isEdit ? $lapangan->fasilitas->pluck('id')->all() : []);
    $jamBuka = old('jam_buka', $isEdit ? \Illuminate\Support\Carbon::parse($lapangan->jam_buka)->format('H:i') : '06:00');
    $jamTutup = old('jam_tutup', $isEdit ? \Illuminate\Support\Carbon::parse($lapangan->jam_tutup)->format('H:i') : '23:00');

    $jenisOlahragaSelectedId = old('jenis_olahraga_id', $isEdit ? $lapangan->jenis_olahraga_id : '');
    $jenisOlahragaSelected = $jenisOlahragaSelectedId
        ? $jenisOlahragas->firstWhere('id', (int) $jenisOlahragaSelectedId)
        : null;
    $jenisOlahragaSelectedName = $jenisOlahragaSelected->name ?? '';

    $fasilitasSelectedObjects = $fasilitas->whereIn('id', $selectedFasilitasIds)->values();

    // Default center peta: Bandung. Kalau lagi edit & sudah ada koordinat, pakai itu.
    $defaultLatitude = old('latitude', $isEdit ? $lapangan->latitude : -6.9175) ?: -6.9175;
    $defaultLongitude = old('longitude', $isEdit ? $lapangan->longitude : 107.6191) ?: 107.6191;
@endphp

{{--
    Leaflet & script pencarian alamat di-bundle lewat Vite (resources/js/lapangan-map.js,
    diimport dari resources/js/app.js), BUKAN dari CDN — supaya tidak blank kalau CDN
    diblokir jaringan. Pastikan sudah `npm install leaflet` & `import './lapangan-map';`
    ditambahkan di resources/js/app.js.
--}}

{{-- Data mentah untuk combobox & tag-selector, dipakai oleh script vanilla JS di bawah --}}
<script id="jenisOlahragaData" type="application/json">
    {!! $jenisOlahragas->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->values()->toJson() !!}
</script>
<script id="fasilitasData" type="application/json">
    {!! $fasilitas->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->values()->toJson() !!}
</script>
<script id="fasilitasSelectedData" type="application/json">
    {!! $fasilitasSelectedObjects->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->values()->toJson() !!}
</script>

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

        {{-- Combobox Jenis Olahraga dengan quick-add --}}
        <div class="relative">
            <label class="mb-2 block text-sm font-semibold text-gray-700">Jenis Olahraga</label>
            <input
                type="text"
                id="jenisOlahragaSearch"
                autocomplete="off"
                placeholder="Cari atau tambah jenis olahraga..."
                value="{{ $jenisOlahragaSelectedName }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10"
            >
            <input type="hidden" name="jenis_olahraga_id" id="jenisOlahragaId" value="{{ $jenisOlahragaSelectedId }}">

            <div id="jenisOlahragaDropdown" class="absolute z-20 mt-1 hidden w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                <ul id="jenisOlahragaList" class="max-h-56 overflow-y-auto text-sm"></ul>
                <button type="button" id="jenisOlahragaAddBtn" class="hidden w-full border-t border-gray-100 px-4 py-3 text-left text-sm font-medium text-green-700 hover:bg-green-50"></button>
            </div>

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

    {{-- Alamat & Lokasi Peta --}}
    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Alamat Lengkap</label>
        <textarea name="alamat" id="alamatInput" rows="2" placeholder="Contoh: Jl. Dago No. 12, Coblong, Bandung, Jawa Barat — atau biarkan kosong, akan terisi otomatis saat kamu pilih titik di peta" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">{{ old('alamat', $lapangan->alamat ?? '') }}</textarea>
        <p class="mt-2 text-xs text-gray-500">Otomatis terisi saat kamu klik/cari titik di peta di bawah, tapi tetap bisa diedit manual.</p>
        @error('alamat') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Titik Lokasi di Peta</label>
        <p class="mb-2 text-xs text-gray-500">Cari alamat, atau klik langsung di peta / geser pin untuk menandai lokasi lapangan.</p>

        <style>
            /*
                #lapanganMapGroup membungkus search-box + peta jadi SATU stacking context lokal
                (isolation: isolate) TANPA menaikkan z-index-nya sendiri. Jadi z-index di dalamnya
                (dropdown hasil pencarian vs peta) cuma dibandingkan sesama dirinya sendiri, dan
                grup ini secara keseluruhan tetap ikut urutan normal terhadap elemen di luar
                dirinya (navbar/sidebar) — tidak akan "lompat" ke atas navbar walau di-scroll.

                Leaflet sendiri menaruh pane & kontrolnya (zoom +/-, attribution) dengan z-index
                sampai 1000. Kalau #lapanganMap tidak punya `position` + stacking context sendiri,
                elemen itu bisa bocor menutupi dropdown pencarian. Makanya #lapanganMap juga diberi
                isolation sendiri di dalam grup ini.
            */
            #lapanganMapGroup {
                position: relative;
                isolation: isolate;
            }

            #lapanganMap {
                position: relative;
                z-index: 0;
                isolation: isolate;
            }

            /* Di dalam grup yang sudah ter-isolate, wrapper pencarian cukup sedikit di atas peta */
            #lapanganMapSearchWrapper {
                position: relative;
                z-index: 10;
            }
        </style>

        <div id="lapanganMapGroup">
            <div id="lapanganMapSearchWrapper" class="relative mb-3">
                <input
                    type="text"
                    id="lapanganMapSearch"
                    autocomplete="off"
                    placeholder="Cari alamat, contoh: Jl. Braga, Bandung"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10"
                >
                <ul id="lapanganMapSearchResults" class="absolute z-30 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg"></ul>
            </div>

            <div id="lapanganMap" data-default-lat="{{ $defaultLatitude }}" data-default-lng="{{ $defaultLongitude }}" class="h-72 w-full overflow-hidden rounded-xl border border-gray-300"></div>
        </div>

        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-500">Latitude</label>
                <input type="number" step="any" name="latitude" id="latitudeInput" value="{{ old('latitude', $lapangan->latitude ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                @error('latitude') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-500">Longitude</label>
                <input type="number" step="any" name="longitude" id="longitudeInput" value="{{ old('longitude', $lapangan->longitude ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">
                @error('longitude') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10">{{ old('deskripsi', $lapangan->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Tag-selector Fasilitas dengan quick-add --}}
    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Fasilitas</label>

        <div id="fasilitasChips" class="mb-3 flex flex-wrap gap-2"></div>

        <div class="relative">
            <input
                type="text"
                id="fasilitasSearch"
                autocomplete="off"
                placeholder="Cari atau tambah fasilitas..."
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-4 focus:ring-green-600/10"
            >
            <div id="fasilitasDropdown" class="absolute z-20 mt-1 hidden w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                <ul id="fasilitasList" class="max-h-56 overflow-y-auto text-sm"></ul>
                <button type="button" id="fasilitasAddBtn" class="hidden w-full border-t border-gray-100 px-4 py-3 text-left text-sm font-medium text-green-700 hover:bg-green-50"></button>
            </div>
        </div>

        {{-- hidden inputs fasilitas_ids[] di-render otomatis oleh JS sesuai chip terpilih --}}
        <div id="fasilitasHiddenInputs"></div>

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

<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const jenisOlahragaData = JSON.parse(document.getElementById('jenisOlahragaData').textContent);
    const fasilitasData = JSON.parse(document.getElementById('fasilitasData').textContent);
    const fasilitasSelectedInitial = JSON.parse(document.getElementById('fasilitasSelectedData').textContent);

    const storeJenisOlahragaUrl = "{{ route('admin.jenis-olahraga.store') }}";
    const storeFasilitasUrl = "{{ route('admin.fasilitas.store') }}";

    // Catatan: logic peta lokasi (Leaflet + cari alamat) sekarang ada di
    // resources/js/lapangan-map.js, di-bundle lewat Vite, bukan di sini.

    function postJson(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(body),
        }).then(async (response) => {
            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.message || 'Terjadi kesalahan, coba lagi.');
            }

            return payload;
        });
    }

    // ---------- Combobox: Jenis Olahraga ----------
    (function initJenisOlahragaCombobox() {
        const options = jenisOlahragaData.slice();
        const searchInput = document.getElementById('jenisOlahragaSearch');
        const hiddenInput = document.getElementById('jenisOlahragaId');
        const dropdown = document.getElementById('jenisOlahragaDropdown');
        const list = document.getElementById('jenisOlahragaList');
        const addBtn = document.getElementById('jenisOlahragaAddBtn');

        function renderList(filterText) {
            const term = filterText.trim().toLowerCase();
            const filtered = term
                ? options.filter((o) => o.name.toLowerCase().includes(term))
                : options;

            list.innerHTML = '';

            if (filtered.length === 0) {
                const empty = document.createElement('li');
                empty.className = 'px-4 py-3 text-gray-400';
                empty.textContent = 'Tidak ada hasil';
                list.appendChild(empty);
            }

            filtered.forEach((option) => {
                const item = document.createElement('li');
                item.className = 'cursor-pointer px-4 py-2.5 hover:bg-green-50';
                item.textContent = option.name;
                item.addEventListener('click', () => selectOption(option));
                list.appendChild(item);
            });

            const exactMatch = term && options.some((o) => o.name.toLowerCase() === term);

            if (term && !exactMatch) {
                addBtn.textContent = '+ Tambah "' + filterText.trim() + '" sebagai jenis olahraga baru';
                addBtn.classList.remove('hidden');
                addBtn.onclick = () => createOption(filterText.trim());
            } else {
                addBtn.classList.add('hidden');
                addBtn.onclick = null;
            }
        }

        function selectOption(option) {
            hiddenInput.value = option.id;
            searchInput.value = option.name;
            closeDropdown();
        }

        function createOption(name) {
            addBtn.disabled = true;
            const originalText = addBtn.textContent;
            addBtn.textContent = 'Menyimpan...';

            postJson(storeJenisOlahragaUrl, { name })
                .then((newOption) => {
                    options.push(newOption);
                    selectOption(newOption);
                })
                .catch((error) => {
                    alert(error.message);
                })
                .finally(() => {
                    addBtn.disabled = false;
                    addBtn.textContent = originalText;
                });
        }

        function openDropdown() {
            dropdown.classList.remove('hidden');
            renderList(searchInput.value);
        }

        function closeDropdown() {
            dropdown.classList.add('hidden');
        }

        searchInput.addEventListener('focus', openDropdown);
        searchInput.addEventListener('input', () => {
            hiddenInput.value = '';
            openDropdown();
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('#jenisOlahragaDropdown') && event.target !== searchInput) {
                closeDropdown();
            }
        });
    })();

    // ---------- Tag-selector: Fasilitas ----------
    (function initFasilitasTagSelector() {
        const options = fasilitasData.slice();
        let selected = fasilitasSelectedInitial.slice();

        const searchInput = document.getElementById('fasilitasSearch');
        const dropdown = document.getElementById('fasilitasDropdown');
        const list = document.getElementById('fasilitasList');
        const addBtn = document.getElementById('fasilitasAddBtn');
        const chipsContainer = document.getElementById('fasilitasChips');
        const hiddenContainer = document.getElementById('fasilitasHiddenInputs');

        function isSelected(id) {
            return selected.some((o) => o.id === id);
        }

        function renderChips() {
            chipsContainer.innerHTML = '';
            hiddenContainer.innerHTML = '';

            selected.forEach((option) => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700';

                const label = document.createElement('span');
                label.textContent = option.name;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'text-green-600 hover:text-green-900';
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', () => removeOption(option));

                chip.appendChild(label);
                chip.appendChild(removeBtn);
                chipsContainer.appendChild(chip);

                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'fasilitas_ids[]';
                hidden.value = option.id;
                hiddenContainer.appendChild(hidden);
            });
        }

        function renderList(filterText) {
            const term = filterText.trim().toLowerCase();
            const available = options.filter((o) => !isSelected(o.id));
            const filtered = term
                ? available.filter((o) => o.name.toLowerCase().includes(term))
                : available;

            list.innerHTML = '';

            if (filtered.length === 0) {
                const empty = document.createElement('li');
                empty.className = 'px-4 py-3 text-gray-400';
                empty.textContent = 'Tidak ada hasil';
                list.appendChild(empty);
            }

            filtered.forEach((option) => {
                const item = document.createElement('li');
                item.className = 'cursor-pointer px-4 py-2.5 hover:bg-green-50';
                item.textContent = option.name;
                item.addEventListener('click', () => selectOption(option));
                list.appendChild(item);
            });

            const exactMatch = term && available.some((o) => o.name.toLowerCase() === term);

            if (term && !exactMatch) {
                addBtn.textContent = '+ Tambah "' + filterText.trim() + '" sebagai fasilitas baru';
                addBtn.classList.remove('hidden');
                addBtn.onclick = () => createOption(filterText.trim());
            } else {
                addBtn.classList.add('hidden');
                addBtn.onclick = null;
            }
        }

        function selectOption(option) {
            selected.push(option);
            renderChips();
            searchInput.value = '';
            renderList('');
            searchInput.focus();
        }

        function removeOption(option) {
            selected = selected.filter((o) => o.id !== option.id);
            renderChips();
            renderList(searchInput.value);
        }

        function createOption(name) {
            addBtn.disabled = true;
            const originalText = addBtn.textContent;
            addBtn.textContent = 'Menyimpan...';

            postJson(storeFasilitasUrl, { name })
                .then((newOption) => {
                    options.push(newOption);
                    selectOption(newOption);
                })
                .catch((error) => {
                    alert(error.message);
                })
                .finally(() => {
                    addBtn.disabled = false;
                    addBtn.textContent = originalText;
                });
        }

        function openDropdown() {
            dropdown.classList.remove('hidden');
            renderList(searchInput.value);
        }

        function closeDropdown() {
            dropdown.classList.add('hidden');
        }

        searchInput.addEventListener('focus', openDropdown);
        searchInput.addEventListener('input', openDropdown);

        document.addEventListener('click', (event) => {
            if (!event.target.closest('#fasilitasDropdown') && event.target !== searchInput) {
                closeDropdown();
            }
        });

        renderChips();
    })();
})();
</script>