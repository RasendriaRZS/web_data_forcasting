<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Asset</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Latar belakang halaman dengan animasi warna bergerak */
        body {
            margin: 0;
            height: 100vh;
            background: transparent;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Desain modal */
        .modal-content {
            border-radius: 15px; /* Sudut yang lebih bulat */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); /* Bayangan lembut */
            background-color: #ffffff; /* Latar belakang putih solid untuk form */
        }

        /* Tombol close merah */
        .btn-close-red {
            color: #ff0000; /* Warna merah untuk tombol X */
            background-color: transparent;
            border: none;
            font-size: 1.5rem; /* Ukuran font lebih besar */
        }

        .btn-close-red:hover {
            color: #cc0000; /* Warna lebih gelap saat hover */
        }

        /* Label form */
        .form-label {
            font-weight: bold; /* Label tebal untuk penekanan */
            color: #333; /* Warna label yang lebih gelap */
        }

        /* Tombol submit */
        .btn-primary {
            background-color: #007bff; /* Warna biru khas Bootstrap */
            border-color: #007bff; /* Border warna biru */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Warna lebih gelap saat hover */
            border-color: #0056b3; /* Border lebih gelap saat hover */
        }

        .modal-dialog {
            max-width: 90%; /* Lebar maksimum modal lebih besar */
        }
    </style>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade show" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog"> <!-- Ukuran modal diatur di CSS -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assetModalLabel">Create New Asset</h5>
                    <!-- Tombol X Merah -->
                    <button type="button" class="btn-close-red" onclick="window.location.href='{{ route('assets.index') }}'" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('assets.store') }}">
                        @csrf

                        <!-- Serial Number -->
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" class="form-control" required />
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required />
                        </div>

                        <!-- Model -->
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <select name="model" id="model" class="form-select" required>
                                <option value="">Select Model</option>
                                <option value="Router">Router</option>
                                <option value="Firewall">Firewall</option>
                                <option value="Access Point">Access Point</option>
                                <option value="Accessories">Accessories</option>
                            </select>
                        </div>

                           <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="In Project">In Project</option> <!-- Opsi baru -->
                                <option value="Warehouse">Warehouse</option> <!-- Opsi baru -->
                                <option value="Maintenance">Maintenance</option> <!-- Opsi sebelumnya -->
                            </select>
                        </div>

                        <!-- Purchase Date -->
                        <div class="mb-3">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" id="purchase_date" class="form-control" required />
                        </div>

                        <!-- Delivery Date -->
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date (Optional)</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="form-control" />
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Submit</button> <!-- Tombol lebar penuh -->

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Script untuk membuka modal secara otomatis dan mencegah penutupan dengan klik di luar modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('assetModal'), {
                backdrop: 'static', // Mencegah modal ditutup dengan klik di luar
                keyboard: false // Mencegah penutupan modal dengan tombol ESC
            });
            myModal.show();
        });
    </script>

</body>
</html>
