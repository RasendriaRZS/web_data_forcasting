@extends('main.main')

@section('main')
<div class="container py-5">
    <div class="row align-items-center mb-4 g-3">
        <div class="col-lg-7 col-12">
            <h1 class="fw-bold" style="font-size:2.4rem;letter-spacing:0.01em;color:#1e293b;">
                <i class="bi bi-archive me-2 text-primary"></i> Asset Master List
            </h1>
        </div>
        <div class="col-lg-5 col-12">
            <form method="GET" action="{{ route('Asset_Master.index') }}">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control border-0 rounded-start-pill px-4"
                        placeholder="Search Serial Number, Name, Project Name"
                        aria-label="Search">
                    <button type="submit" class="btn btn-primary rounded-end-pill px-4">
                        <i class="bi bi-search me-2"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Jumlah Asset Master -->
    <div class="mb-3">
        <span class="fw-semibold text-secondary" style="font-size:1.15rem;">
            Total Data: {{ $assets->total() }}
        </span>
    </div>

        <!-- Petunjuk Scroll -->
    <div class="d-flex align-items-center gap-2 mb-2">
        <span class="text-muted" style="font-size:0.98rem;">
            <i class="bi bi-arrow-right-circle me-1 animate-panah-kanan"></i>
            Geser ke samping untuk melihat semua kolom tabel
        </span>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive rounded-4" style="overflow-x:auto;">
                <table class="table align-middle mb-0" style="font-size:1.10rem;min-width:1250px;">
                    <thead style="background:linear-gradient(90deg,#2563eb 0%,#60a5fa 100%);color:#fff;">
                        <tr>
                            <th class="py-3" style="min-width:60px;text-align:center;">No</th>
                            <th class="py-3" style="min-width:140px;">Serial Number</th>
                            <th class="py-3" style="min-width:130px;">Name</th>
                            <th class="py-3" style="min-width:150px;">Project Name</th>
                            <th class="py-3" style="min-width:120px;">Model</th>
                            <th class="py-3" style="min-width:120px;">Status</th>
                            <th class="py-3" style="min-width:150px;">Asset Received</th>
                            <th class="py-3" style="min-width:150px;">Asset Shipped</th>
                            <th class="py-3" style="min-width:130px;">Location</th>
                            <th class="py-3" style="min-width:200px;">Notes</th>
                            <th class="py-3" style="min-width:170px;">Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                        <tr class="row-hover">
                            <td style="text-align:center; font-weight:600; color:#64748b;">
                                {{ ($assets->currentPage() - 1) * $assets->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <a href="{{ route('assets.detail', $asset->id) }}" class="sn-modern-link badge bg-light text-dark border border-1 shadow-sm" style="font-family:'JetBrains Mono',monospace;font-size:1rem;">
    {{ $asset->serial_number }}
</a>
                            </td>

                            <td class="fw-medium">{{ $asset->name }}</td>
                            <td>{{ $asset->project_name ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border border-1 px-3 py-2 shadow-sm" style="font-size:1rem;">
                                    {{ $asset->model }}
                                </span>
                            </td>
                            <td>
                                @if(strtolower($asset->status) == 'maintenance')
                                    <span class="badge rounded-pill bg-danger text-white shadow-sm px-3 py-2" style="font-size:1rem;">
                                        {{ $asset->status }}
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-info text-dark shadow-sm px-3 py-2" style="font-size:1rem;">
                                        {{ $asset->status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $asset->asset_recieved ? \Carbon\Carbon::parse($asset->asset_recieved)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                {{ $asset->asset_shipped ? \Carbon\Carbon::parse($asset->asset_shipped)->format('d M Y') : '-' }}
                            </td>
                            <td class="fw-medium">{{ $asset->location ?? '-' }}</td>
                            <td class="text-truncate" style="max-width: 240px;" title="{{ $asset->notes }}">
                                {{ $asset->notes ?? '-' }}
                            </td>
                            <td>
                                <span class="text-secondary" style="font-size:1rem;">
                                    {{ $asset->updated_at ? \Carbon\Carbon::parse($asset->updated_at)->format('d M Y H:i') : '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">No assets found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</div>

<style>
body {
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    background: #f8fafc;
}
h1, .fw-bold {
    letter-spacing: .01em;
}
.input-group.input-group-lg {
    border-radius: 2rem;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(37,99,235,0.06);
    background: #fff;
}
.input-group .form-control {
    font-size: 1.12rem;
    border: none;
    background: #f4f7fa;
    border-top-left-radius: 2rem !important;
    border-bottom-left-radius: 2rem !important;
}
.input-group .form-control:focus {
    border: none;
    box-shadow: none;
    background: #e0e7ef;
}
.input-group .btn {
    font-size: 1.12rem;
    border-top-right-radius: 2rem !important;
    border-bottom-right-radius: 2rem !important;
    box-shadow: none;
}
.table thead th {
    vertical-align: middle;
    font-weight: 600;
    letter-spacing: .04em;
    border-bottom: 2px solid #2563eb;
    text-align: center;
}
.table td, .table th {
    vertical-align: middle;
    padding-top: 1.15rem;
    padding-bottom: 1.15rem;
}
.row-hover:hover {
    background-color: #f1f5f9;
    transition: background-color 0.3s;
}
.badge {
    font-size: 1rem;
    font-weight: 500;
    letter-spacing: .02em;
}
.card {
    border-radius: 1.5rem;
}
.card-footer {
    border-top: none;
}
.table-responsive {
    border-radius: 1.1rem;
    overflow-x: auto;
    background: #fff;
}
@media (max-width: 991.98px) {
    .input-group.input-group-lg {
        border-radius: 1.2rem;
    }
    .input-group .form-control,
    .input-group .btn {
        font-size: 1rem;
        border-radius: 1.2rem !important;
    }
    .table-responsive {
        min-width: 100vw;
    }
}
</style>
@endsection
