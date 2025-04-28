<nav class="navbar navbar-expand-lg navbar-light px-3 bg-light">
    <div class="container-fluid">
        <a href="/dashboard" class="d-flex align-items-center me-md-auto text-black text-decoration-none">
            <img src="{{ asset('assets/logo.svg') }}" alt="Dashboard" style="width: 25px; height: 25px; margin-right: 10px;">
            <span class="fs-4">Admin Panel</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                {{-- Tambahkan menu di sini jika ada --}}
            </ul>

            {{-- Notifikasi --}}
            <div class="nav-item me-3">
                <a class="nav-link position-relative" href="{{ route('admin.notifications.index') }}">
                    <i class="fa-solid fa-bell fs-5"></i>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- Language Dropdown --}}
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ app()->getLocale() === 'en' ? 'Language' : 'Bahasa' }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                    <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">English</a></li>
                    <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">Indonesia</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
