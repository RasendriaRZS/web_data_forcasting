@extends('main.main')

@section('main')
<div class="container py-5">
    <h1 class="fw-bold mb-4" style="font-size:2.2rem;letter-spacing:0.01em;color:#1e293b;">
        <i class="bi bi-box-seam me-2 text-primary"></i> Asset Management
    </h1>
    
<div class="container">
    <h2>Import Assets via CSV</h2>
    <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">Upload CSV File:</label>
            <input type="file" name="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Import</button>
    </form>
</div>



    <!-- Tombol Create dan Search/Filter -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-3 col-12 mb-2 mb-md-0">
            <a href="{{ route('assets.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="font-size:1.08rem; border-radius: 0.8rem; min-width: 140px; max-width: 70%;">
                <i class="bi bi-plus-circle"></i>
                Create New Asset
            </a>
        </div>
        <div class="col-md-9 col-12">
            <form method="GET" action="{{ route('assets.index') }}" class="d-flex flex-wrap gap-2 align-items-center justify-content-md-end">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="max-width:180px;" placeholder="Search">
                <select name="status" class="form-select" style="max-width:150px;" onchange="this.form.submit()">
                    <option value="">All Asset</option>
                    <option value="In Project" {{ request('status') == 'In Project' ? 'selected' : '' }}>In Project</option>
                    <option value="Warehouse" {{ request('status') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                    <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan Jumlah Aset -->
    <div class="mb-3">
        <span class="fw-semibold text-secondary" style="font-size:1.15rem;">
            Total Assets: {{ $assets->count() }}
        </span>
    </div>

    <!-- Petunjuk Scroll -->
    <div class="d-flex align-items-center gap-2 mb-2">
        <span class="text-muted" style="font-size:0.98rem;">
            <i class="bi bi-arrow-right-circle me-1 animate-panah-kanan"></i>
            Swipe sideways to see all table columns.
        </span>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
         <!-- Scrollbar horizontal atas -->
        <div id="top-scroll" class="top-scrollbar mb-1" style="overflow-x:auto; overflow-y:hidden; height:18px;">
            <div style="height:1px; min-width:1100px;"></div>
        </div>


            <div class="table-responsive rounded-4" style="overflow-x:auto;">
                <table class="table align-middle mb-0 table-striped" style="font-size:1.07rem;min-width:1100px;">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:140px;">Serial Number</th>
                            <th style="min-width:120px;">Name</th>
                            <th style="min-width:110px;">Model</th>
                            <th style="min-width:120px;">Status</th>
                            <th style="min-width:150px;">Project Name</th>
                            <th style="min-width:140px;">Asset Received</th>
                            <th style="min-width:140px;">Asset Shipped</th>
                            <th style="min-width:120px;">Location</th>
                            <th style="min-width:160px;">Notes</th>
                            <th style="min-width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                        <tr>
                            <td>
                                <span class="fw-semibold text-dark" style="font-family:'JetBrains Mono',monospace;font-size:1rem;">
                                    {{ $asset->serial_number }}
                                </span>
                            </td>

                            <td>{{ $asset->name }}</td>
                            <td>
                                <span class="badge bg-light text-dark border border-1 px-3 py-2 shadow-sm">{{ $asset->model }}</span>
                            </td>
                            <td>
                                @if(strtolower($asset->status) == 'maintenance')
                                    <span class="badge bg-danger text-white px-3 py-2 shadow-sm">{{ $asset->status }}</span>
                                @else
                                    <span class="badge bg-info text-dark px-3 py-2 shadow-sm">{{ $asset->status }}</span>
                                @endif
                            </td>
                            <td>{{ $asset->project_name ?? '-' }}</td>
                            <td>{{ $asset->purchase_date }}</td>
                            <td>{{ $asset->delivery_date ?? '-' }}</td>
                            <td>{{ $asset->location ?? '-' }}</td>
                            <td class="text-truncate" style="max-width: 160px;" title="{{ $asset->notes }}">{{ $asset->notes ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-warning btn-sm px-3" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm px-3" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No assets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white border-0 py-4">
                <div class="d-flex flex-column align-items-center gap-2">
                    <div class="text-muted" style="font-size:1rem;">
                        page <b>{{ $assets->currentPage() }}</b> from <b>{{ $assets->lastPage() }}</b>
                        &mdash; Total: <b>{{ $assets->total() }}</b> data
                    </div>
                    <nav aria-label="Asset Master Pagination">
                        {{ $assets->onEachSide(1)->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop Up Detail -->
    <!-- <div class="modal fade" id="assetDetailModal" tabindex="-1" aria-labelledby="assetDetailModalLabel" aria-hidden="true">
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
                    <div id="detail-history" class="text-muted" style="white-space: pre-line;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light" style="border-bottom-left-radius: 18px; border-bottom-right-radius: 18px;">
          </div>
        </div>
      </div>
    </div> -->

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
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f8fafc;
    }
    .badge {
        font-size: .97rem;
        font-weight: 500;
        letter-spacing: .01em;
    }
    .table td, .table th {
        vertical-align: middle;
        padding-top: 1.05rem;
        padding-bottom: 1.05rem;
    }
    .btn-sm {
        font-size: 1rem;
        padding: 0.35rem 0.7rem;
        border-radius: 0.5rem;
    }
    /* Animasi panah scroll */
    .animate-panah-kanan {
        animation: panah-kanan 1.2s infinite alternate;
    }
    @keyframes panah-kanan {
        0% { transform: translateX(0); opacity: 0.7;}
        100% { transform: translateX(12px); opacity: 1;}
    }

    .pagination {
    --bs-pagination-bg: #f4f7fa;
    --bs-pagination-border-radius: 0.6rem;
    --bs-pagination-padding-x: 1rem;
    --bs-pagination-padding-y: 0.5rem;
    --bs-pagination-color: #2563eb;
    --bs-pagination-hover-bg: #e0e7ff;
    --bs-pagination-hover-color: #1e40af;
    --bs-pagination-active-bg: linear-gradient(90deg,#2563eb 0%,#60a5fa 100%);
    --bs-pagination-active-color: #fff;
    --bs-pagination-border-width: 0;
    box-shadow: 0 2px 8px rgba(37,99,235,0.08);
    }
    .pagination .page-link {
        color: var(--bs-pagination-color);
        border-radius: 0.6rem !important;
        margin: 0 0.15rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
    }
    .pagination .page-link:focus, .pagination .page-link:hover {
        background: var(--bs-pagination-hover-bg);
        color: var(--bs-pagination-hover-color);
    }
    .pagination .active .page-link {
        background: var(--bs-pagination-active-bg);
        color: var(--bs-pagination-active-color);
        border: none;
    }

    .top-scrollbar {
    background: #f8fafc;
    border-radius: 0.7rem 0.7rem 0 0;
    box-shadow: 0 1px 3px rgba(37,99,235,0.07);
    scrollbar-width: thin;
    scrollbar-color: #2563eb #e0e7ff;
}
.top-scrollbar::-webkit-scrollbar {
    height: 8px;
    background: #e0e7ff;
    border-radius: 8px;
}
.top-scrollbar::-webkit-scrollbar-thumb {
    background: #2563eb;
    border-radius: 8px;
}
.table-responsive {
    border-radius: 0 0 1.1rem 1.1rem;
}

    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var detailLinks = document.querySelectorAll('.asset-detail-link');
        detailLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                document.getElementById('detail-serial').textContent = this.getAttribute('data-serial');
                document.getElementById('detail-name').textContent = this.getAttribute('data-name');
                document.getElementById('detail-model').textContent = this.getAttribute('data-model');
                document.getElementById('detail-history').innerHTML = this.getAttribute('data-history');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const topScroll = document.getElementById('top-scroll');
    const tableScroll = document.querySelector('.table-responsive');

    // Sinkron scroll atas ke bawah
    topScroll.addEventListener('scroll', function() {
        tableScroll.scrollLeft = topScroll.scrollLeft;
    });

    // Sinkron scroll bawah ke atas
    tableScroll.addEventListener('scroll', function() {
        topScroll.scrollLeft = tableScroll.scrollLeft;
    });

    // Set lebar scrollbar atas sesuai tabel
    function syncScrollWidth() {
        const table = tableScroll.querySelector('table');
        topScroll.firstElementChild.style.width = table.offsetWidth + 'px';
    }
    syncScrollWidth();
    window.addEventListener('resize', syncScrollWidth);
    });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @endsection
