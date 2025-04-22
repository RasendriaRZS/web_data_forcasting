@extends('main.main')

@section('main')
<div class="container mt-5 custom-container">
    <h1 class="mb-4">Asset Management</h1>

    <!-- Tombol Create New Asset & Form Search/Filter -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Tombol Create New Asset -->
        <a href="{{ route('assets.create') }}" class="btn btn-primary d-flex align-items-center" style="gap: 5px;">
            <i class="bi bi-plus-circle"></i>
            Create New Asset
        </a>

        <!-- Form Search dan Filter Status -->
        <form method="GET" action="{{ route('assets.index') }}" class="d-flex" style="gap: 10px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                   placeholder="Search">
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
        <table class="table table-bordered table-striped">
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
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->status }}</td>
                    <td>{{ $asset->project_name ?? '-' }}</td>
                    <td>{{ $asset->purchase_date }}</td>
                    <td>{{ $asset->delivery_date ?? '-' }}</td>
                    <td>{{ $asset->notes ?? '-' }}</td>
                    <td class="text-center">
                        <!-- Tombol Edit dengan Ikon Pensil -->
                        <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <!-- Tombol Delete dengan Ikon Tong Sampah -->
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
@endsection
