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

    {{-- <!-- Card Statistik -->
    <div class="row g-4 mb-4">
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0 rounded-4 text-center py-3">
                <div class="fw-semibold text-secondary mb-1">Total Asset</div>
                <div class="fw-bold text-primary" style="font-size:2rem;">
                    {{ $totalAsset ?? '-' }}
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0 rounded-4 text-center py-3">
                <div class="fw-semibold text-secondary mb-1">Asset Growth</div>
                <div class="fw-bold text-success" style="font-size:2rem;">
                    {{ $growthPercent ?? '-' }}<span style="font-size:1.1rem;">%</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0 rounded-4 text-center py-3">
                <div class="fw-semibold text-secondary mb-1">Most Used Asset</div>
                <div class="fw-bold text-dark" style="font-size:1.3rem;">
                    {{ $mostUsedAsset ?? '-' }}
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Filter -->
    <form id="filterForm" method="GET" action="{{ route('analytics') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">Tahun Awal</label>
                <select name="start_year" class="form-select shadow-sm">
                    <option value="">Pilih Tahun Awal</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('start_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">Tahun Akhir</label>
                <select name="end_year" class="form-select shadow-sm">
                    <option value="">Pilih Tahun Akhir</option>
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
            <canvas id="analyticsChart" height="220"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let analyticsChart = null;

    function initChart(labels, historicalValues, probabilityValues) {
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
                        label: 'Probabilities',
                        data: probabilityValues,
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
    const probabilities = @json($probabilities);

    // Gabungkan semua tahun dan urutkan
    const labels = [...new Set([
        ...historicalData.map(item => item.year),
        ...probabilities.map(item => item.year)
    ])].sort((a, b) => a - b);

    // Mapping nilai untuk setiap tahun
    const historicalValues = labels.map(year => {
        const item = historicalData.find(d => d.year === year);
        return item ? item.value : null;
    });

    const probabilityValues = labels.map(year => {
        const item = probabilities.find(d => d.year === year);
        return item ? item.value : null;
    });

    // Inisialisasi chart
    initChart(labels, historicalValues, probabilityValues);
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
