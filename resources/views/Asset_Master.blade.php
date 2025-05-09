@extends('main.main')

@section('main')
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Asset Master List</h1>
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('Asset_Master.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search Serial Number, Name, Project Name">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Serial Number</th>
                    <th>Name</th>
                    <th>Project Name</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Asset Received</th>
                    <th>Asset Shipped</th>
                    <th>Location</th>
                    <th>Notes</th>
                    <th>Last Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                <tr>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->project_name }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->status }}</td>
                    <td>{{ $asset->asset_recieved }}</td>
                    <td>{{ $asset->asset_shipped }}</td>
                    <td>{{ $asset->location }}</td>
                    <td>{{ $asset->notes }}</td>
                    <td>{{ $asset->updated_at ? $asset->updated_at->format('d M Y H:i') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No assets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $assets->links() }}
        </div>
    </div>
</div>
@endsection
