@extends('main.main')

@section('main')
<div class="container mt-5 custom-container">
    <h1 class="mb-4">Asset Management</h1>

    <div class="mb-5">
        <a href="{{ route('assets.create') }}" class="btn btn-primary">Create New Asset</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Filter -->
    <form method="GET" action="{{ route('assets.index') }}" class="mb-4">
        <div class="input-group">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Asset</option>
                <option value="In Project" {{ request('status') == 'In Project' ? 'selected' : '' }}>In Project</option>
                <option value="Warehouse" {{ request('status') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <button type="submit" class="btn btn-outline-secondary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Serial Number</th>
                    <th>Name</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Purchase Date</th>
                    <th>Delivery Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                <tr>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->status }}</td>
                    <td>{{ $asset->purchase_date }}</td>
                    <td>{{ $asset->delivery_date ?? '-' }}</td>
                    <td>{{ $asset->notes ?? '-' }}</td>
                    <td class="text-center">
                        <!-- Tombol Edit dengan Ikon Pensil -->
                        <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil-fill"></i> <!-- Ikon Pensil -->
                        </a>

                        <!-- Tombol Delete dengan Ikon Tong Sampah -->
                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="bi bi-trash-fill"></i> <!-- Ikon Tong Sampah -->
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($assets->isEmpty())
            <tr><td colspan="8" class="text-center">No assets found.</td></tr>
        @endif
    </div>

@endsection
