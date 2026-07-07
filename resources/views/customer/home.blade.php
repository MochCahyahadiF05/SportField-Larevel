<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportField - Sewa Lapangan Olahraga</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/SportFields.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-nav-footer.css') }}">
    <script src="https://kit.fontawesome.com/80f227685e.js" crossorigin="anonymous"></script>
</head>
<body>
    @include('customer.partials.navbar')

    <section id="home" class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content animate-fade-in">
                    <div class="badge">
                        <span>Platform #1 Penyewaan Lapangan</span>
                    </div>
                    <h1 class="hero-title">
                        <span class="gradient-text">Sewa Lapangan</span>
                        <br>
                        <span>Jadi Lebih Mudah</span>
                    </h1>
                    <p class="hero-description">Booking lapangan olahraga favorit Anda hanya dalam hitungan detik. Fasilitas lengkap, harga terjangkau, dan pengalaman terbaik menanti Anda.</p>
                    <div class="hero-buttons">
                        <a href="#lapangan" class="btn-primary">
                            <span>Lihat Lapangan</span>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ \App\Models\Lapangan::count() }}</div>
                            <div class="stat-label">Lapangan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4.9</div>
                            <div class="stat-label">Rating</div>
                        </div>
                    </div>
                </div>

                <div class="slider-container">
                    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1459865264687-595d652de67e?q=80&w=2070&auto=format&fit=crop');">
                        <div class="slide-overlay"></div>
                        <div class="slide-content">
                            <h3>Futsal Premium</h3>
                            <p>Rumput sintetis terbaik</p>
                        </div>
                    </div>
                    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?q=80&w=2070&auto=format&fit=crop');">
                        <div class="slide-overlay"></div>
                        <div class="slide-content">
                            <h3>Badminton Indoor</h3>
                            <p>AC & pencahayaan premium</p>
                        </div>
                    </div>
                    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1519861531473-9200262188bf?q=80&w=2070&auto=format&fit=crop');">
                        <div class="slide-overlay"></div>
                        <div class="slide-content">
                            <h3>Mini Soccer</h3>
                            <p>Lapangan luas & nyaman</p>
                        </div>
                    </div>

                    <div class="slider-dots">
                        <button class="dot active" onclick="goToSlide(0)"></button>
                        <button class="dot" onclick="goToSlide(1)"></button>
                        <button class="dot" onclick="goToSlide(2)"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="lapangan" class="lapangan-section">
        <div class="container">
            <div class="section-header">
                <h2>Pilihan <span class="gradient-text">Lapangan</span></h2>
                <p>Berbagai pilihan lapangan dengan fasilitas terbaik</p>
            </div>

            <div class="cards-grid">
                @forelse ($lapanganUnggulan as $lapangan)
                    <article class="field-card">
                        <div class="card-image">
                            <img src="{{ $lapangan->gambar ? asset('storage/' . $lapangan->gambar) : asset('assets/img/Logo.png') }}" alt="{{ $lapangan->nama_lapangan }}">
                        </div>
                        <div class="card-body">
                            <div class="card-header">
                                <h3>{{ $lapangan->nama_lapangan }}</h3>
                                <span class="badge-type">{{ $lapangan->jenisOlahraga?->name ?? 'Olahraga' }}</span>
                            </div>
                            <p class="card-description">{{ \Illuminate\Support\Str::limit($lapangan->deskripsi ?? 'Lapangan siap digunakan.', 60) }}</p>
                            <div class="card-footer">
                                <div class="price">
                                    <span class="price-amount">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                                    <span class="price-unit">/jam</span>
                                </div>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <span>{{ number_format($lapangan->reviews_avg_rating ?? 0, 1) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('customer.bookings.create', $lapangan) }}" class="btn-booking">Booking Sekarang</a>
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                        <p>Belum ada lapangan tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="testimoni-section">
        <div class="container">
            <div class="section-header">
                <h2>Testimoni <span class="gradient-text">Pelanggan</span></h2>
                <p>Kepuasan pelanggan adalah kebanggaan kami</p>
            </div>

            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="star-rating"><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span></div>
                    <p class="testimonial-text">"Pelayanan sangat baik dan lapangan dalam kondisi prima."</p>
                    <div class="testimonial-author"><h4>Budi Santoso</h4><p>Futsal Enthusiast</p></div>
                </div>
                <div class="testimonial-card">
                    <div class="star-rating"><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span></div>
                    <p class="testimonial-text">"Harga terjangkau dengan kualitas lapangan yang bagus."</p>
                    <div class="testimonial-author"><h4>Siti Nurhaliza</h4><p>Badminton Player</p></div>
                </div>
                <div class="testimonial-card">
                    <div class="star-rating"><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span></div>
                    <p class="testimonial-text">"Fasilitas lengkap dan lokasi strategis."</p>
                    <div class="testimonial-author"><h4>Ahmad Wijaya</h4><p>Team Captain</p></div>
                </div>
                <div class="testimonial-card">
                    <div class="star-rating"><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span></div>
                    <p class="testimonial-text">"Interface yang user-friendly dan proses cepat."</p>
                    <div class="testimonial-author"><h4>Rini Putri</h4><p>Regular Customer</p></div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="contact-section">
        <div class="container-small">
            <div class="section-header">
                <h2>Hubungi <span class="gradient-text">Kami</span></h2>
                <p>Ada pertanyaan? Kami siap membantu Anda</p>
            </div>

            <form class="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="john@example.com">
                    </div>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="tel" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label>Pesan</label>
                    <textarea rows="5" placeholder="Tulis pesan Anda disini..."></textarea>
                </div>
                <button type="submit" class="btn-submit">Kirim Pesan</button>
            </form>
        </div>
    </section>

    @include('customer.partials.footer')
    <script src="{{ asset('assets/js/customer-home.js') }}"></script>
</body>
</html>