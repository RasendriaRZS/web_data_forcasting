<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Website Data Forecasting</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Plus Jakarta Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins:500,600&display=swap' rel='stylesheet'> <!-- Font Ikonik -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}"> <!-- CSS Kustom -->
    
</head>
<body>

<div class="navbar">
    <!-- Brand -->
    <div class="brand">
        <h1>DF-apps</h1>
        <h6>Data Forecasting</h6> <!-- Deskripsi kecil -->
    </div>

    <!-- Garis Pemisah -->
    <div class="divider"></div>

    <!-- Menu Navbar -->
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-house"></i> Dashboard
    </a>
    <a href="{{ route('assets.index') }}" class="{{ request()->is('assets') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i> Asset
    </a>    
    <a href="/analytics" class="{{ request()->is('analytics') ? 'active' : '' }}">
        <i class="bi bi-graph-up"></i> Analytics
    </a>
    <a href="/Asset_Master" class="{{ request()->is('Asset_Master') ? 'active' : '' }}">
        <i class="bi bi-archive"></i> Asset Master
    </a>

    <!-- Area Notifikasi dan Profil -->
    <div class="profile-area">
        <a href="#" class="me-3 notification-icon" data-bs-toggle="modal" data-bs-target="#notificationModal">
            <i class="bi bi-bell-fill"></i> <!-- Ikon Notifikasi -->
            @if(isset($hasNewMaintenance) && $hasNewMaintenance)
                <span class="badge-notification">!</span>
            @endif
        </a>

        <div class="divider"></div> <!-- Garis pemisah -->

        <!-- Link untuk simbol orang -->
        <!-- <a href="/home" style="color:white;"> -->
            <!-- <i class="bi bi-person-circle"></i> Ikon Orang -->
            <!-- <span>Nama Akun</span> Nama akun pengguna -->
        <!-- </a> -->

        {{-- <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul> --}}
                   <ul class="navbar-nav ms-auto align-items-center gap-2">
    @guest
        @if (Route::has('login'))
           <li class="nav-item">
                <a class="nav-link px-4 py-2 rounded-3 fw-semibold border border-1 border-primary bg-white shadow-sm transition"
                href="{{ route('login') }}"
                style="font-size: 1.08rem; letter-spacing:0.01em; color: #2563eb;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('Login') }}
                </a>
            </li>

        @endif

        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link px-4 py-2 rounded-3 fw-semibold text-secondary border border-1 border-secondary bg-white shadow-sm transition"
                   href="{{ route('register') }}">
                    <i class="bi bi-person-plus me-1"></i> {{ __('Register') }}
                </a>
            </li>
        @endif
    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm bg-light border border-1 border-primary fw-semibold"
               href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
               style="transition: background 0.2s, color 0.2s;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff&size=32"
                     alt="Avatar" class="rounded-circle" width="32" height="32">
                <span>{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown" style="min-width: 160px;">
                <a class="dropdown-item py-2 text-danger d-flex align-items-center gap-2"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    @endguest
</ul>



    </div>
</div>

<div class="main">
    @yield('main')
</div>

<!-- Modal Notifikasi -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-primary text-black">
                <h5 class="modal-title d-flex align-items-center" id="notificationModalLabel">
                    <i class="bi bi-bell me-2"></i> Notifications
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if($maintenanceModels->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($maintenanceModels->sortByDesc('created_at') as $model) <!-- Mengurutkan berdasarkan waktu terbaru -->
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom p-3 notification-item" style="background-color: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-circle-fill text-warning me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>{{ $model->name }}</strong>
                                        <small class="text-muted d-block">Created at: {{ $model->created_at->format('d-m-Y H:i') }}</small>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary toggle-details" data-bs-target="#details-{{ $model->id }}">
                                    View
                                </button>
                            </li>
                            <div class="collapse" id="details-{{ $model->id }}">
                                <div class="card card-body mt-2">
                                    <p><strong>Detail:</strong> {{ $model->serial_number ?? 'Not Available' }} - {{ $model->model ?? 'Not Available' }} - {{ $model->status ?? 'Not Available' }}</p>
                                    <p><strong>Purchase Date:</strong> {{ $model->purchase_date ? $model->purchase_date->format('d-m-Y') : 'Not Available' }}</p>
                                    <p><strong>Delivery Date:</strong> 
                                        @if($model->delivery_date)
                                            {{ $model->delivery_date->format('d-m-Y') }}
                                        @else
                                            <span class="text-muted">Not Available</span>
                                        @endif
                                    </p>
                                    <p><strong>Note:</strong> {{ $model->notes ?? 'Not Available' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <!-- Jika tidak ada notifikasi -->
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle-fill text-success mb-2" style="font-size: 2rem;"></i>
                        <p>No notifications available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<style>
/* Navbar and dropdown modern styling */
.navbar-nav .nav-link,
.navbar-nav .dropdown-toggle {
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.navbar-nav .nav-link:hover,
.navbar-nav .dropdown-toggle:hover {
    background: #e0e7ff !important;
    color: #1e40af !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.12);
}
.dropdown-menu {
    border-radius: 0.8rem;
    border: none;
    box-shadow: 0 8px 24px rgba(30, 64, 175, 0.10);
    padding: 0.5rem 0.2rem;
    min-width: 160px;
}
.dropdown-item {
    border-radius: 0.5rem;
    transition: background 0.18s, color 0.18s;
}
.dropdown-item:hover, .dropdown-item:focus {
    background: #e0e7ff;
    color: #2563eb;
}

.navbar-nav .nav-link {
    color: #2563eb !important;
    background: #fff !important;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.navbar-nav .nav-link:hover,
.navbar-nav .nav-link:focus {
    background: #e0e7ff !important;
    color: #2563eb !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.12);
    text-decoration: none;
}
</style>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript untuk mengubah tombol View menjadi Close -->
<script>
    // Mengubah tombol View menjadi Close saat detail ditampilkan atau disembunyikan
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target'); // Ambil ID target collapse
            const targetElement = document.querySelector(targetId); // Cari elemen collapse berdasarkan ID

            if (targetElement.classList.contains('show')) {
                this.innerText = "View"; // Kembalikan teks tombol menjadi View saat detail disembunyikan
                targetElement.classList.remove('show'); // Sembunyikan detail
            } else {
                this.innerText = "Close"; // Ubah teks tombol menjadi Close saat detail ditampilkan
                targetElement.classList.add('show'); // Tampilkan detail
            }
        });
    });

    @if(isset($showToast) && $showToast)
        window.onload = function() {
            showNotificationToast();
        };
    @endif
</script>

<!-- animasi -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
