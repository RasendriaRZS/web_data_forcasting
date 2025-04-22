@extends('main.main')

@section('main')
<div class="container mt-5 custom-container">
    <h1 class="mb-4">Asset Management</h1>

    <!-- Tombol Create New Asset & Form Search/Filter -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('assets.create') }}" class="btn btn-primary d-flex align-items-center" style="gap: 5px;">
            <i class="bi bi-plus-circle"></i>
            Create New Asset
        </a>
        <form method="GET" action="{{ route('assets.index') }}" class="d-flex" style="gap: 10px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                   placeholder="Search Serial Number, Name, Project Name">
            <select name="status" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All Asset</option>
                <option value="In Project" {{ request('status') == 'In Project' ? 'selected' : '' }}>In Project</option>
                <option value="Warehouse" {{ request('status') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <button type="submit" class="btn btn-outline-secondary">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('assets.index') }}" class="btn btn-outline-danger ms-2">Reset</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan Jumlah Aset -->
    <div class="mb-3">
        <strong>Total Assets: {{ $assets->count() }}</strong>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Serial Number</th>
                    <th>Name</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Project Name</th>
                    <th>Purchase Date</th>
                    <th>Delivery Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                <tr>
                    <td>
                        <a href="#"
                           class="asset-detail-link sn-modern-link"
                           data-bs-toggle="modal"
                           data-bs-target="#assetDetailModal"
                           data-serial="{{ $asset->serial_number }}"
                           data-name="{{ $asset->name }}"
                           data-model="{{ $asset->model }}"
                           data-history="-">
                            {{ $asset->serial_number }}
                        </a>
                    </td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->status }}</td>
                    <td>{{ $asset->project_name ?? '-' }}</td>
                    <td>{{ $asset->purchase_date }}</td>
                    <td>{{ $asset->delivery_date ?? '-' }}</td>
                    <td>{{ $asset->notes ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No assets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- modal pop up  --}}
    <div class="modal fade" id="assetDetailModal" tabindex="-1" aria-labelledby="assetDetailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 18px;">
          <div class="modal-header bg-primary bg-gradient text-white" style="border-top-left-radius: 18px; border-top-right-radius: 18px;">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-info-circle-fill fs-3"></i>
              <div>
                <h5 class="modal-title mb-0" id="assetDetailModalLabel">Asset Detail</h5>
                <small class="text-white-50">View asset information and history</small>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="padding: 2rem;">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                  <span class="badge bg-info bg-gradient me-2 p-2 rounded-circle"><i class="bi bi-upc-scan"></i></span>
                  <div>
                    <div class="fw-bold text-secondary">Serial Number</div>
                    <div id="detail-serial" class="fs-5"></div>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                  <span class="badge bg-success bg-gradient me-2 p-2 rounded-circle"><i class="bi bi-tag"></i></span>
                  <div>
                    <div class="fw-bold text-secondary">Name</div>
                    <div id="detail-name" class="fs-5"></div>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                  <span class="badge bg-warning bg-gradient me-2 p-2 rounded-circle"><i class="bi bi-cpu"></i></span>
                  <div>
                    <div class="fw-bold text-secondary">Model</div>
                    <div id="detail-model" class="fs-5"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                  <div class="card-body">
                    <div class="fw-bold text-secondary mb-2"><i class="bi bi-clock-history"></i> History</div>
                    <div id="detail-history" class="text-muted">-</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light" style="border-bottom-left-radius: 18px; border-bottom-right-radius: 18px;">
          </div>
        </div>
      </div>
    </div>

    <!-- CSS Modern untuk Serial Number -->
    <style>
    .sn-modern-link {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none !important;
        transition: color 0.2s, background 0.2s;
        border-radius: 4px;
        padding: 2px 4px;
    }
    .sn-modern-link:hover, .sn-modern-link:focus {
        color: #1d4ed8;
        background: #e0e7ff;
        outline: none;
        text-decoration: none !important;
    }
    /* Modal backdrop blur */
    .modal-backdrop.show {
        backdrop-filter: blur(2px);
    }
    </style>

    <!-- Script untuk isi modal dinamis -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var detailLinks = document.querySelectorAll('.asset-detail-link');
        detailLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                document.getElementById('detail-serial').textContent = this.getAttribute('data-serial');
                document.getElementById('detail-name').textContent = this.getAttribute('data-name');
                document.getElementById('detail-model').textContent = this.getAttribute('data-model');
                document.getElementById('detail-history').textContent = this.getAttribute('data-history');
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
