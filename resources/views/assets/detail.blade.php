@extends('main.main')

@section('main')
<style>
    .asset-header {
        background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
        color: #fff;
        border-radius: 1rem 1rem 0 0;
        padding: 2rem 2rem 1.5rem 2rem;
        box-shadow: 0 4px 24px rgba(30, 64, 175, 0.08);
        margin-bottom: -1px;
    }
    .asset-metadata strong {
        color: #2563eb;
        min-width: 120px;
        display: inline-block;
    }
    .asset-metadata .value {
        font-weight: 500;
        color: #1e293b;
        margin-left: 0.5rem;
    }
    .asset-card {
        border-radius: 1rem;
        overflow: hidden;
        border: none;
    }
    .asset-summary {
        background: #f8fafc;
        border-radius: 0.8rem;
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.04);
    }
    .history-table th, .history-table td {
        vertical-align: middle;
    }
    .badge-status {
        font-size: 1rem;
        padding: 0.5em 1em;
        border-radius: 0.6em;
    }
    .badge-status.maintenance {
        background: #f87171;
        color: #fff;
    }
    .badge-status.active {
        background: #60a5fa;
        color: #fff;
    }
    .badge-status.warehouse {
        background: #fbbf24;
        color: #fff;
    }
    .badge-status.default {
        background: #e5e7eb;
        color: #374151;
    }
    .stat-box {
        background: #f1f5f9;
        border-radius: 0.7em;
        padding: 1.2em 1em;
        text-align: center;
        box-shadow: 0 1px 4px rgba(30,64,175,0.05);
        margin-bottom: 1em;
    }
    .stat-box h5 {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 0.4em;
    }
    .stat-box .stat {
        font-size: 2rem;
        font-weight: bold;
        color: #2563eb;
    }
    .history-table thead {
        background: #f1f5f9;
    }
</style>

<div class="container py-4">
    <div class="asset-card shadow mb-4">
        <div class="asset-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-1" style="letter-spacing:0.01em;">
                    <i class="bi bi-box-seam me-2"></i> Asset Details
                </h2>
                <div class="fs-5 fw-semibold text-white-50">
                    Serial Number: {{ $asset->serial_number }}
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                @php
                    $statusClass = match(strtolower($asset->status)) {
                        'maintenance' => 'maintenance',
                        'warehouse' => 'warehouse',
                        'active' => 'active',
                        default => 'default'
                    };
                @endphp
                <span class="badge badge-status {{ $statusClass }}">
                    {{ ucfirst($asset->status) }}
                </span>
            </div>
        </div>
        <div class="asset-summary">
            <div class="row g-4 asset-metadata">
                <div class="col-md-6">
                    <div class="mb-2"><strong>Name:</strong> <span class="value">{{ $asset->name }}</span></div>
                    <div class="mb-2"><strong>Model:</strong> <span class="value">{{ $asset->model }}</span></div>
                    <div class="mb-2"><strong>Project:</strong> <span class="value">{{ $asset->project_name ?? '-' }}</span></div>
                    <div class="mb-2"><strong>Location:</strong> <span class="value">{{ $locationStatus ?? '-' }}</span></div>
                    <div class="mb-2"><strong>Notes:</strong> <span class="value">{{ $asset->notes ?? '-' }}</span></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Asset Received:</strong> <span class="value">{{ $asset->purchase_date }}</span></div>
                    <div class="mb-2"><strong>Asset Shipped:</strong> <span class="value">{{ $asset->delivery_date ?? '-' }}</span></div>
                    <div class="mb-2"><strong>Date Insert:</strong> <span class="value">{{ $dateInsert }}</span></div>
                    <div class="mb-2"><strong>Date Delete:</strong> <span class="value">{{ $dateDelete ?? '-' }}</span></div>
                </div>
            </div>
        </div>
        <div class="row g-4 px-4 pb-3">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <h5>Jumlah Update/Edit</h5>
                    <div class="stat">{{ $updateCount }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <h5>Jumlah Delete</h5>
                    <div class="stat">{{ $deleteCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-2 mt-4">History Perubahan</h4>
    <div class="card shadow-sm mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover history-table mb-0">
                    <thead>
                        <tr>
                            <th style="min-width:140px;">Tanggal</th>
                            <th style="min-width:90px;">Aksi</th>
                            <th style="min-width:200px;">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                        <tr>
                            <td>{{ $history->created_at }}</td>
                            <td>
                                <span class="badge
                                    {{ $history->action == 'insert' ? 'bg-success' : ($history->action == 'update' ? 'bg-info text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($history->action) }}
                                </span>
                            </td>
                            <td>{{ $history->description ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada riwayat perubahan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="{{ route('assets.index') }}" class="btn btn-outline-primary mt-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Asset
    </a>
</div>
@endsection
