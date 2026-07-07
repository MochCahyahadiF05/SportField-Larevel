<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lapangan - SportField</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-nav-footer.css') }}">
    <script src="https://kit.fontawesome.com/80f227685e.js" crossorigin="anonymous"></script>
    <style>
        .detail-shell { padding: 8rem 1rem 5rem; }
        .detail-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; align-items: start; }
        .panel { background: #fff; border: 1px solid #dbe7de; border-radius: 1.25rem; box-shadow: 0 10px 25px rgba(15, 23, 42, .05); }
        .panel-pad { padding: 1.25rem; }
        .breadcrumbs { display: flex; gap: .5rem; align-items: center; color: #64748b; font-size: .9rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .breadcrumbs a { color: #64748b; text-decoration: none; }
        .breadcrumbs span:last-child { color: #16a34a; font-weight: 600; }
        .cover-card { position: relative; overflow: hidden; border-radius: 1.5rem; min-height: 420px; background: #e5e7eb; }
        .cover-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .cover-badge { position: absolute; right: 1rem; top: 1rem; background: #16a34a; color: #fff; padding: .55rem .95rem; border-radius: 9999px; font-weight: 700; font-size: .9rem; }
        .booking-card { position: sticky; top: 6rem; }
        .booking-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
        .booking-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0; }
        .booking-subtitle { color: #64748b; font-size: .95rem; margin-top: .25rem; }
        .info-chip { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .8rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-size: .85rem; font-weight: 600; margin: .25rem .4rem .25rem 0; }
        .booking-form .form-group { margin-bottom: 1rem; }
        .booking-form label { display: block; margin-bottom: .5rem; font-weight: 600; color: #0f5132; }
        .booking-form input, .booking-form select, .booking-form textarea { width: 100%; padding: .85rem 1rem; border-radius: .9rem; border: 1px solid #d1d5db; background: #fff; font: inherit; outline: none; }
        .booking-form input:focus, .booking-form select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22, 163, 74, .12); }
        .booking-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .summary-box { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0 0; margin-top: .5rem; border-top: 1px solid #e5e7eb; font-weight: 700; }
        .total-price { color: #16a34a; font-size: 1.4rem; }
        .submit-btn { width: 100%; border: 0; padding: 1rem; border-radius: .95rem; background: linear-gradient(to right, #16a34a, #22c55e); color: #fff; font-weight: 800; cursor: pointer; margin-top: 1rem; }
        .submit-btn:hover { filter: brightness(.98); }
        .slot-note { font-size: .88rem; color: #64748b; }
        .slot-note strong { color: #0f172a; }
        @media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } .booking-card { position: static; } }
    </style>
</head>
<body>
    @include('customer.partials.navbar')
    @include('customer.partials.toast')

    @php
        $jamBukaText = \Illuminate\Support\Carbon::parse($lapangan->jam_buka)->format('H:i');
        $jamTutupText = \Illuminate\Support\Carbon::parse($lapangan->jam_tutup)->format('H:i');
    @endphp

    @if ($errors->any())
        <div id="sfToast" class="sf-toast sf-toast-error" role="status" aria-live="polite">
            <div class="sf-toast-icon">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div class="sf-toast-body">
                <strong>Gagal</strong>
                <p>{{ $errors->first() }}</p>
            </div>
            <button type="button" class="sf-toast-close" aria-label="Tutup toast" onclick="document.getElementById('sfToast')?.remove()">&times;</button>
        </div>
    @endif

    <section class="detail-shell">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('home') }}#lapangan">Lapangan</a>
                <span>/</span>
                <span>{{ $lapangan->nama_lapangan }}</span>
            </div>

            @if ($errors->any())
                <div class="panel panel-pad" style="border-color:#fecaca; background:#fef2f2; color:#b91c1c; margin-bottom: 1rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="detail-grid">
                <div>
                    <div class="cover-card">
                        <img src="{{ $lapangan->gambar ? asset('storage/' . $lapangan->gambar) : asset('assets/img/Logo.png') }}" alt="{{ $lapangan->nama_lapangan }}">
                        <div class="cover-badge">{{ $lapangan->jenisOlahraga?->name ?? 'Olahraga' }}</div>
                    </div>

                    <div class="panel panel-pad" style="margin-top: 1rem;">
                        <h3 style="margin:0 0 .5rem; font-size: 1.35rem; font-weight: 800; color:#0f172a;">{{ $lapangan->nama_lapangan }}</h3>
                        <div class="slot-note" style="margin-bottom: .75rem;">{{ $lapangan->deskripsi ?: 'Lapangan siap digunakan.' }}</div>

                        <div>
                            <span class="info-chip"><i class="fa-solid fa-clock"></i> {{ $jamBukaText }} - {{ $jamTutupText }}</span>
                            <span class="info-chip"><i class="fa-solid fa-tag"></i> Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</span>
                            <span class="info-chip"><i class="fa-solid fa-circle-check"></i> {{ $lapangan->status === 'tersedia' ? 'Aktif' : 'Perbaikan' }}</span>
                        </div>

                        <div style="margin-top: 1rem;">
                            <strong style="display:block; margin-bottom:.5rem; color:#0f172a;">Fasilitas</strong>
                            <div class="slot-note">{{ $lapangan->fasilitas->pluck('name')->implode(', ') ?: '-' }}</div>
                        </div>

                        <div style="margin-top: 1rem;">
                            <strong style="display:block; margin-bottom:.5rem; color:#0f172a;">Slot tersedia</strong>
                            <div class="slot-note">{{ $bookedCount }} jam sudah dibooking pada tanggal ini.</div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-pad booking-card">
                    <div class="booking-head">
                        <div>
                            <h2 class="booking-title">Booking Lapangan</h2>
                            <div class="booking-subtitle">Pilih tanggal dan jam. Slot yang sudah dibooking otomatis nonaktif.</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('customer.bookings.store', $lapangan) }}" class="booking-form" id="bookingForm">
                        @csrf

                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $selectedDate) }}" min="{{ now()->toDateString() }}" required>
                            @error('tanggal') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                        </div>

                        <div class="booking-grid-2">
                            <div class="form-group">
                                <label for="jam_mulai">Pilih Jam Mulai</label>
                                <select id="jam_mulai" name="jam_mulai" required>
                                    <option value="">-- Pilih Jam --</option>
                                    @foreach ($timeSlots as $slot)
                                        <option value="{{ $slot['value'] }}" @selected(old('jam_mulai') === $slot['value']) @disabled($slot['blocked'])>
                                            {{ $slot['label'] }}{{ $slot['blocked'] ? ' (Sudah Dibooking)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jam_mulai') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="jam_selesai">Pilih Jam Selesai</label>
                                <select id="jam_selesai" name="jam_selesai" required>
                                    <option value="">-- Pilih Jam --</option>
                                </select>
                                @error('jam_selesai') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="summary-box">
                            <span>Total Harga</span>
                            <span class="total-price" id="totalHarga">Rp 0</span>
                        </div>

                        <button type="submit" class="submit-btn">Lanjut ke Pembayaran</button>
                    </form>

                    <div class="slot-note" style="margin-top: .9rem;">
                        Slot yang bertabrakan dengan booking <strong>pending</strong> atau <strong>confirmed</strong> tidak bisa dipilih.
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('customer.partials.footer')

    <script src="{{ asset('assets/js/customer-home.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timeSlots = @json($timeSlots);
            const closingTime = @json($jamTutupText);
            const hasSuccessToast = @json(session()->has('success'));
            const hourlyPrice = {{ (int) $lapangan->harga_per_jam }};
            const dateInput = document.getElementById('tanggal');
            const startSelect = document.getElementById('jam_mulai');
            const endSelect = document.getElementById('jam_selesai');
            const totalHarga = document.getElementById('totalHarga');

            const timeToMinutes = (time) => {
                const parts = time.split(':').map(Number);
                return (parts[0] * 60) + parts[1];
            };

            const formatMoney = (value) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(value).replace('IDR', 'Rp').trim();

            const slotIsFree = (startTime, endTime) => {
                const startIndex = timeSlots.findIndex((slot) => slot.value === startTime);
                const boundaryIndex = timeSlots.findIndex((slot) => slot.end_value === endTime) + 1;

                if (startIndex < 0 || boundaryIndex <= startIndex) {
                    return false;
                }

                for (let index = startIndex; index < boundaryIndex; index += 1) {
                    if (timeSlots[index] && timeSlots[index].blocked) {
                        return false;
                    }
                }

                return true;
            };

            const updateTotalHarga = () => {
                const selectedStart = startSelect.value;
                const selectedEnd = endSelect.value;

                if (!selectedStart || !selectedEnd) {
                    totalHarga.textContent = 'Rp 0';
                    return;
                }

                const durationHours = (timeToMinutes(selectedEnd) - timeToMinutes(selectedStart)) / 60;

                if (durationHours <= 0) {
                    totalHarga.textContent = 'Rp 0';
                    return;
                }

                totalHarga.textContent = formatMoney(durationHours * hourlyPrice);
            };

            const renderEndOptions = () => {
                const selectedStart = startSelect.value;
                const previousEnd = @json(old('jam_selesai'));

                endSelect.innerHTML = '<option value="">-- Pilih Jam --</option>';

                if (!selectedStart) {
                    updateTotalHarga();
                    return;
                }

                const startIndex = timeSlots.findIndex((slot) => slot.value === selectedStart);

                if (startIndex < 0) {
                    updateTotalHarga();
                    return;
                }

                for (let index = startIndex + 1; index <= timeSlots.length; index += 1) {
                    const candidateEnd = index === timeSlots.length ? closingTime : timeSlots[index].value;

                    if (!slotIsFree(selectedStart, candidateEnd)) {
                        continue;
                    }

                    const option = document.createElement('option');
                    option.value = candidateEnd;
                    option.textContent = candidateEnd;

                    if (previousEnd && previousEnd === candidateEnd) {
                        option.selected = true;
                    }

                    endSelect.appendChild(option);
                }

                updateTotalHarga();
            };

            dateInput.addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('tanggal', this.value);
                window.location.href = url.toString();
            });

            startSelect.addEventListener('change', renderEndOptions);
            endSelect.addEventListener('change', updateTotalHarga);

            renderEndOptions();
            updateTotalHarga();

            if (hasSuccessToast) {
                setTimeout(function () {
                    window.location.href = '{{ route('home') }}';
                }, 2500);
            }
        });
    </script>
</body>
</html>