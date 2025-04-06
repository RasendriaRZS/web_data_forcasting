@extends('main.main')

@section('main')
<div class="container">
    <h1>Analytics Overview</h1>
    
    <!-- Form Filter -->
    <form id="filterForm" method="GET" action="{{ route('analytics') }}">
        <div class="row mb-3">
            <div class="col-md-3">
                <select name="start_year" class="form-select">
                    <option value="">Pilih Tahun Awal</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('start_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="end_year" class="form-select">
                    <option value="">Pilih Tahun Akhir</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('end_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <canvas id="analyticsChart" width="400" height="200"></canvas>
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
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderWidth: 2,
                        tension: 0.4
                    },
                    {
                        label: 'Probabilities',
                        data: probabilityValues,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
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
                        title: { display: true, text: 'Year' },
                        type: 'category'
                    },
                    y: { 
                        title: { display: true, text: 'Value' }, 
                        beginAtZero: true 
                    }
                }
            }
        });
    }

    // Ambil data dari controller
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
        return item ? item.value : null; // Null jika tahun tidak ada di data historis
    });

    const probabilityValues = labels.map(year => {
        const item = probabilities.find(d => d.year === year);
        return item ? item.value : null; // Null jika tahun tidak ada di probabilitas
    });

    // Inisialisasi chart
    initChart(labels, historicalValues, probabilityValues);
</script>
@endsection
