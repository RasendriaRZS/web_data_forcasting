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
        <i class="bi bi-cogs"></i> Asset
    </a>    
    <a href="/analytics" class="{{ request()->is('analytics') ? 'active' : '' }}">
        <i class="bi bi-bar-chart"></i> Analytics
    </a>
    <a href="/services" class="{{ request()->is('settings') ? 'active' : '' }}">
        <i class="bi bi-gear"></i> Settings
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
        <a href="/profile" style="color:white;">
            <i class="bi bi-person-circle"></i> <!-- Ikon Orang -->
            <span>Nama Akun</span> <!-- Nama akun pengguna -->
        </a>
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
