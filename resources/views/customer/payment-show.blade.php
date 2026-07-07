<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SportField</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-nav-footer.css') }}">
    <script src="https://kit.fontawesome.com/80f227685e.js" crossorigin="anonymous"></script>
    <style>
        .payment-shell { padding: 8rem 1rem 5rem; }
        .payment-grid { display: grid; grid-template-columns: 1.15fr .85fr; gap: 1.5rem; align-items: start; }
        .panel { background: #fff; border: 1px solid #dbe7de; border-radius: 1.25rem; box-shadow: 0 10px 25px rgba(15, 23, 42, .05); }
        .panel-pad { padding: 1.25rem; }
        .title { font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0 0 .5rem; }
        .subtitle { color: #64748b; font-size: .96rem; }
        .row { display: flex; justify-content: space-between; gap: 1rem; padding: .85rem 0; border-bottom: 1px solid #e5e7eb; }
        .row:last-child { border-bottom: 0; }
        .label { color: #64748b; }
        .value { font-weight: 700; color: #0f172a; text-align: right; }
        .badge { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .85rem; border-radius: 9999px; font-size: .85rem; font-weight: 700; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-berhasil { background: #dcfce7; color: #15803d; }
        .badge-gagal { background: #fee2e2; color: #b91c1c; }
        .pay-btn { width: 100%; border: 0; padding: 1rem; border-radius: .95rem; background: linear-gradient(to right, #16a34a, #22c55e); color: #fff; font-weight: 800; cursor: pointer; }
        .helper { font-size: .9rem; color: #64748b; }
        .helper strong { color: #0f172a; }
        @media (max-width: 1024px) { .payment-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('customer.partials.navbar')
    @include('customer.partials.toast')

    @php
        $status = $payment->status;
        $badgeClass = match ($status) {
            'berhasil' => 'badge-berhasil',
            'gagal' => 'badge-gagal',
            default => 'badge-pending',
        };
    @endphp

    <section class="payment-shell">
        <div class="container">
            <div class="payment-grid">
                <div class="panel panel-pad">
                    <p style="text-transform:uppercase; letter-spacing:.2em; color:#16a34a; font-weight:700; font-size:.8rem; margin-bottom:.6rem;">Pembayaran Midtrans</p>
                    <h1 class="title">Selesaikan pembayaran booking kamu</h1>
                    <p class="subtitle">Klik tombol bayar untuk membuka Snap Midtrans. Setelah berhasil, status pembayaran akan diperbarui otomatis.</p>

                    <div style="margin-top:1.25rem;">
                        <div class="row">
                            <span class="label">Lapangan</span>
                            <span class="value">{{ $booking->lapangan?->nama_lapangan ?? '-' }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Tanggal</span>
                            <span class="value">{{ $booking->tanggal ? \Illuminate\Support\Carbon::parse($booking->tanggal)->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Jam</span>
                            <span class="value">{{ $booking->jam_mulai ? \Illuminate\Support\Carbon::parse($booking->jam_mulai)->format('H:i') : '-' }} - {{ $booking->jam_selesai ? \Illuminate\Support\Carbon::parse($booking->jam_selesai)->format('H:i') : '-' }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Order ID</span>
                            <span class="value">{{ $payment->midtrans_order_id ?? '-' }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Status</span>
                            <span class="value"><span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span></span>
                        </div>
                        <div class="row">
                            <span class="label">Total</span>
                            <span class="value">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="panel panel-pad">
                    <h2 style="margin:0 0 .6rem; font-size:1.3rem; font-weight:800; color:#0f172a;">Aksi Pembayaran</h2>
                    <p class="helper" style="margin-bottom:1rem;">Jika status masih <strong>pending</strong>, buka Snap Midtrans untuk memilih metode pembayaran.</p>

                    @if ($status === 'berhasil')
                        <div class="panel panel-pad" style="background:#f0fdf4; border-color:#bbf7d0; margin-bottom:1rem;">
                            Pembayaran berhasil diterima. Booking kamu sudah dikonfirmasi.
                        </div>
                        <a href="{{ route('home') }}" class="pay-btn" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">Kembali ke Beranda</a>
                    @elseif ($status === 'gagal')
                        <div class="panel panel-pad" style="background:#fef2f2; border-color:#fecaca; margin-bottom:1rem;">
                            Pembayaran gagal atau dibatalkan. Silakan coba lagi jika masih ingin booking.
                        </div>
                        @if ($paymentConfigured && $payment->snap_token)
                            <button type="button" class="pay-btn" id="payButton">Bayar Sekarang</button>
                        @endif
                    @else
                        @if (! $paymentConfigured)
                            <div class="panel panel-pad" style="background:#fefce8; border-color:#fde68a; margin-bottom:1rem;">
                                Konfigurasi Midtrans belum tersedia. Tambahkan <strong>MIDTRANS_SERVER_KEY</strong> dan <strong>MIDTRANS_CLIENT_KEY</strong> di file .env untuk mengaktifkan pembayaran.
                            </div>
                        @elseif ($payment->snap_token)
                            <button type="button" class="pay-btn" id="payButton">Bayar Sekarang</button>
                        @else
                            <div class="panel panel-pad" style="background:#fefce8; border-color:#fde68a; margin-bottom:1rem;">
                                Token pembayaran belum tersedia. Hubungi admin jika tombol bayar tidak muncul.
                            </div>
                        @endif
                    @endif

                    <div class="helper" style="margin-top:1rem;">
                        <strong>Catatan:</strong> pembayaran pending akan tampil di menu admin Pembayaran setelah notifikasi Midtrans diterima.
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($paymentConfigured && $payment->snap_token)
        <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const token = @json($payment->snap_token);
                const payButton = document.getElementById('payButton');

                const refreshPage = () => {
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                };

                const openSnap = () => {
                    if (!window.snap) {
                        return;
                    }

                    window.snap.pay(token, {
                        onSuccess: function () {
                            refreshPage();
                        },
                        onPending: function () {
                            refreshPage();
                        },
                        onError: function () {
                            refreshPage();
                        },
                        onClose: function () {
                            // tetap di halaman ini supaya customer bisa lanjut pembayaran lagi
                        },
                    });
                };

                if (payButton) {
                    payButton.addEventListener('click', openSnap);
                    if (@json($payment->status === 'pending')) {
                        setTimeout(openSnap, 600);
                    }
                }
            });
        </script>
    @endif

    @include('customer.partials.footer')
</body>
</html>