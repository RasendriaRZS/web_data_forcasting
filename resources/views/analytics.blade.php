@extends('main.main')

@section('main')
<div class="container py-5">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="fw-bold text-primary" style="font-size:2.2rem;">
            <i class="bi bi-graph-up"></i> Analytics Overview
        </h1>
        <div class="text-muted mb-2" style="font-size:1.1rem;">Predicting Asset Needs & Trends</div>
    </div>

    <!-- Filter -->
    <form id="filterForm" method="GET" action="{{ route('analytics') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">Start Year</label>
                <select name="start_year" class="form-select shadow-sm">
                    <option value="">Select Start Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('start_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">End Year</label>
                <select name="end_year" class="form-select shadow-sm">
                    <option value="">Select End Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('end_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Chart -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-graph-up text-primary" style="font-size:1.4rem;"></i>
                <span class="fw-semibold text-secondary" style="font-size:1.1rem;">Asset Analytics Chart</span>
            </div>
            <canvas id="analyticsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Model Evaluation Metrics -->
    <div class="card shadow-sm border-0 rounded-4 my-4 p-4">
    <h5 class="fw-semibold text-secondary mb-3">Model Evaluation Metrics</h5>
    @if(is_null($mae) || is_null($rmse) || is_null($mape))
        <p class="text-muted fst-italic">Data tidak cukup untuk melakukan evaluasi model.</p>
    @else
        <ul class="list-group list-group-flush" style="font-size:1.1rem;">
            <li class="list-group-item">Mean Absolute Error (MAE): <strong>{{ number_format($mae, 4) }}</strong></li>
            <li class="list-group-item">Root Mean Squared Error (RMSE): <strong>{{ number_format($rmse, 4) }}</strong></li>
            <li class="list-group-item">Mean Absolute Percentage Error (MAPE): <strong>{{ number_format($mape, 2) }}%</strong></li>
        </ul>
    @endif
</div>

    <!-- Devices in Maintenance -->
    <div class="card shadow-sm border-0 rounded-4 my-4 p-4">
        <h5 class="fw-semibold text-secondary mb-3">Devices in Maintenance</h5>
        @if($maintenanceModels->isEmpty())
            <p class="text-muted">No devices currently in maintenance.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach($maintenanceModels as $device)
                    <li class="list-group-item">
                        {{ $device->name ?? 'Unnamed Device' }} - Purchased: {{ \Carbon\Carbon::parse($device->purchase_date)->format('Y-m-d') }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let analyticsChart = null;

    function initChart(labels, historicalValues, predictionValues) {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        
        if (analyticsChart) {
            analyticsChart.destroy();
        }

        analyticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Historical Data',
                        data: historicalValues,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.13)',
                        borderWidth: 2,
                        tension: 0.4
                    },
                    {
                        label: 'Predictions',
                        data: predictionValues,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.12)',
                        borderWidth: 2,
                        tension: 0.4,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true, position: 'top' } },
                scales: {
                    x: { 
                        title: { display: true, text: 'Year', font: { weight: 'bold', size: 14 } },
                        type: 'category',
                        ticks: { font: { size: 13 } }
                    },
                    y: { 
                        title: { display: true, text: 'Value', font: { weight: 'bold', size: 14 } }, 
                        beginAtZero: true,
                        ticks: { font: { size: 13 } }
                    }
                }
            }
        });
    }

    // Data dari controller
    const historicalData = @json($data);
    const predictions = @json($predictions);

    // Gabungkan semua tahun dan urutkan
    const labels = [...new Set([
        ...historicalData.map(item => item.year),
        ...predictions.map(item => item.year)
    ])].sort((a, b) => a - b);

    // Mapping nilai untuk setiap tahun
    const historicalValues = labels.map(year => {
        const item = historicalData.find(d => d.year === year);
        return item ? item.value : null;
    });

    const predictionValues = labels.map(year => {
        const item = predictions.find(d => d.year === year);
        return item ? item.value : null;
    });

    // Inisialisasi chart
    initChart(labels, historicalValues, predictionValues);
</script>

<style>
body {
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
}
h1, .fw-bold {
    letter-spacing: .01em;
}
.card {
    border-radius: 1.5rem;
}
.form-label {
    font-size: 1rem;
}
.form-select, .btn, .form-control {
    font-size: 1.07rem;
    border-radius: 0.8rem;
}
canvas#analyticsChart {
    background: #fff;
    border-radius: 1.1rem;
    box-shadow: 0 1px 8px rgba(37,99,235,0.04);
    margin-top: 10px;
}
</style>
@endsection