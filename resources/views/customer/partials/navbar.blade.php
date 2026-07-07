<nav class="navbar">
    <div class="container">
        <div class="nav-content">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="{{ asset('assets/img/Logo.png') }}" alt="SportField logo">
                </div>
                <h1 class="logo-text">SportField</h1>
            </div>

            <ul class="nav-menu">
                <li><a href="#home" class="active">Beranda</a></li>
                <li><a href="#lapangan">Lapangan</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    @php
                        $user = auth()->user();
                        $nameParts = preg_split('/\s+/', trim($user->name ?? 'User'));
                        $initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
                        if (!empty($nameParts[1])) {
                            $initials .= strtoupper(substr($nameParts[1], 0, 1));
                        }
                    @endphp

                    <div class="user-menu">
                        <button type="button" id="profileDropdownBtn" class="user-profile-btn" aria-haspopup="true" aria-expanded="false">
                            <span class="avatar">{{ $initials }}</span>
                            <span class="user-name">{{ $user->name }}</span>
                            <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <div id="profileDropdown" class="dropdown-menu" role="menu">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                                <svg class="dropdown-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profil Saya
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="dropdown-logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-item-logout" role="menuitem" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                                    <svg class="dropdown-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-register">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>