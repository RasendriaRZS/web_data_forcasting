@extends('main.main')

@section('main')
<div class="container py-4">
    <h1 class="mb-1 fw-bold">Analytics Overview</h1>
    <h5 class="text-muted mb-4"><i>Asset Acquisition Predictions with ARIMA Model</i></h5>
    
    <!-- Form Filter -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body py-3">
            <form id="filterForm" method="GET" action="{{ route('analytics') }}">
                <div class="row g-3 align-items-end justify-content-center">
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1 fw-semibold">Start Year</label>
                        <select name="start_year" class="form-select">
                            <option value="">All Available</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('start_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1 fw-semibold">End Year</label>
                        <select name="end_year" class="form-select">
                            <option value="">All Available</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('end_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-grid mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <canvas id="analyticsChart" style="min-height:320px;max-height:420px;"></canvas>
        </div>
    </div>

    <!-- Legend -->
    <div class="d-flex justify-content-center gap-4 mt-3">
        <div><span class="badge bg-primary me-2" style="width:18px;height:18px;vertical-align:middle;"></span> Historical Data</div>
        <div><span class="badge bg-warning me-2" style="width:18px;height:18px;vertical-align:middle;"></span> ARIMA Predictions</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let analyticsChart = null;

    function initChart(labels, historicalData, predictions) {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        if (analyticsChart) analyticsChart.destroy();

        analyticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Historical Asset Acquisitions',
                        data: historicalData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        borderWidth: 3,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#2563eb',
                        fill: true
                    },
                    {
                        label: 'ARIMA Predictions',
                        data: predictions,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.10)',
                        borderWidth: 3,
                        tension: 0.4,
                        borderDash: [6, 6],
                        pointRadius: 5,
                        pointBackgroundColor: '#ffc107'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 16, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#222',
                        titleFont: { size: 16, weight: 'bold' },
                        bodyFont: { size: 14 }
                    }
                },
                layout: {
                    padding: 20
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Year',
                            color: '#6c757d',
                            font: { size: 15, weight: 'bold' }
                        },
                        grid: { display: false },
                        ticks: { font: { size: 14 } }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Assets',
                            color: '#6c757d',
                            font: { size: 15, weight: 'bold' }
                        },
                        beginAtZero: true,
                        grace: '5%',
                        grid: { color: '#e8e8e8' },
                        ticks: { font: { size: 14 } }
                    }
                }
            }
        });
    }

    // Process data from controller
    const historicalData = @json($data);
    const arimaPredictions = @json($probabilities);

    // Combine all unique years
    const allYears = [
        ...new Set([
            ...historicalData.map(d => d.year),
            ...arimaPredictions.map(d => d.year)
        ])
    ].sort((a, b) => a - b);

    // Map values to years
    const historicalValues = allYears.map(year => 
        historicalData.find(d => d.year === year)?.value || null
    );
    const predictionValues = allYears.map(year => 
        arimaPredictions.find(d => d.year === year)?.value || null
    );

    // Initialize chart
    initChart(
        allYears.map(String), 
        historicalValues,
        predictionValues
    );
</script>
@endsection
