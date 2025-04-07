<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
    <style>
        /* Latar belakang dengan warna netral */
        body {
            margin: 0;
            background-color: #f8f9fa; /* Warna latar belakang netral */
        }

        /* Desain modal */
        .modal-content {
            border-radius: 15px; /* Sudut yang lebih bulat */
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
            background-color: #ffffff; /* Latar belakang putih solid untuk form */
        }

        .modal-header {
            border-bottom: none; /* Menghilangkan border bawah pada header modal */
        }

        .modal-title {
            color: #007bff; /* Warna biru khas Bootstrap untuk judul */
        }

        /* Tombol close dengan ikon */
        .btn-close-red {
            color: #dc3545; /* Warna merah untuk tombol X */
            background-color: transparent;
            border: none;
            font-size: 1.5rem; /* Ukuran font lebih besar */
        }

        .btn-close-red:hover {
            color: #c82333; /* Warna lebih gelap saat hover */
        }

        /* Label form */
        .form-label {
            font-weight: bold; /* Label tebal untuk penekanan */
            color: #495057; /* Warna label yang lebih gelap */
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
            max-width: 50%; /* Lebar maksimum modal lebih besar */
        }
    </style>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade show" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog"> <!-- Ukuran modal diatur di CSS -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assetModalLabel"><i class="bi bi-pencil-fill"></i> Edit Asset</h5>
                    <!-- Tombol X Merah -->
                    <button type="button" class="btn-close-red" onclick="window.location.href='{{ route('assets.index') }}'" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('assets.update', $asset->id) }}">
                        @csrf
                        @method('PUT') <!-- Menentukan method PUT untuk update -->

                        <!-- Form Horizontal -->
                        <div class="row mb-3">
                            <!-- Serial Number -->
                            <div class="col-md-6">
                                <label for="serial_number" class="form-label">Serial Number</label>
                                <input type="text" name="serial_number" id="serial_number" class="form-control shadow-sm" value="{{ $asset->serial_number }}" required />
                            </div>

                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control shadow-sm" value="{{ $asset->name }}" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Model -->
                            <div class="col-md-6">
                                <label for="model" class="form-label">Model</label>
                                <select name="model" id="model" class="form-select shadow-sm" required>
                                    <option value="">Select Model</option>
                                    <option value="Router" {{ $asset->model == 'Router' ? 'selected' : '' }}>Router</option>
                                    <option value="Firewall" {{ $asset->model == 'Firewall' ? 'selected' : '' }}>Firewall</option>
                                    <option value="Access Point" {{ $asset->model == 'Access Point' ? 'selected' : '' }}>Access Point</option>
                                    <option value="Accessories" {{ $asset->model == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select shadow-sm" required>
                                    <option value="">Select Status</option>
                                    <option value="In Project" {{ $asset->status == 'In Project' ? 'selected' : '' }}>In Project</option> 
                                    <option value="Warehouse" {{ $asset->status == 'Warehouse' ? 'selected' : '' }}>Warehouse</option> 
                                    <option value="Maintenance" {{ $asset->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option> 
                                </select>
                            </div>
                        </div>

                        <!-- Purchase Date & Delivery Date -->
                        <div class = "row mb-3">
                            <!-- Purchase Date -->
                            <div class = "col-md-6">
                                <label for = "purchase_date", class = "form-label">Purchase Date</label> 
                                <input type = "date", name = "purchase_date", id = "purchase_date", class = "form-control shadow-sm", value="{{ $asset->purchase_date }}" required/>
                            </div>

                            <!-- Delivery Date -->
                            <div class = "col-md-6">
                                <label for = "delivery_date", class = "form-label">Delivery Date (Optional)</label> 
                                <input type = "date", name = "delivery_date", id = "delivery_date", class = "form-control shadow-sm" value="{{ $asset->delivery_date }}"/>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class = "mb-3">
                            <label for = "notes", class = "form-label">Notes (Optional)</label> 
                            <textarea name = "notes", id = "notes", rows = "3", class = "form-control shadow-sm">{{ $asset->notes }}</textarea> 
                        </div>

                        <!-- Submit Button -->
                        <button type = "submit", class = "btn btn-primary w-100 shadow-sm"><i class='bi bi-check-circle'></i> Update Asset</button>

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
