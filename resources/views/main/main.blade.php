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
    <link rel="stylesheet" href="css/navbar.css"> <!-- CSS Kustom -->
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
        <a href="#" class="me-3" data-bs-toggle="modal" data-bs-target="#notificationModal">
            <i class="bi bi-bell-fill notification-icon"></i> <!-- Ikon Notifikasi -->
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
    <div class="modal-content">
      <!-- Header Modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
        <!-- Tombol tutup X merah -->
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Body Modal -->
      <div class="modal-body">
        @if($maintenanceModels->isNotEmpty())
            @foreach($maintenanceModels as $model)
                <!-- Menampilkan nama model dan waktu pembuatan -->
                <div class="notification-item mb-2">
                    {{ $model->name }} - Dibuat pada: {{ $model->created_at->format('H:i') }}
                </div>
            @endforeach
        @else
            <!-- Jika tidak ada notifikasi -->
            <p>Tidak ada notifikasi.</p>
        @endif
      </div>
      {{-- Footer modal dihapus --}}
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- animasi -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
