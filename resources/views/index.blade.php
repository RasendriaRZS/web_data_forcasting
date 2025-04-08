@extends('main.main')

@section('main')
<div class="container mt-5">
    <!-- 4 Box Model -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-router display-4 text-primary"></i>
                    <h5 class="mt-2">Router</h5>
                    <h3>{{ $modelCounts['Router'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-shield-lock display-4 text-danger"></i>
                    <h5 class="mt-2">Firewall</h5>
                    <h3>{{ $modelCounts['Firewall'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-wifi display-4 text-success"></i>
                    <h5 class="mt-2">Access Point</h5>
                    <h3>{{ $modelCounts['Access Point'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-usb-plug display-4 text-warning"></i>
                    <h5 class="mt-2">Accessories</h5>
                    <h3>{{ $modelCounts['Accessories'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Tengah -->
    <div class="row">
        <!-- Bagian Kiri: Pie Chart -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Summary Asset</h5>
                </div>
                <div class="card-body">
                    <!-- Atur tinggi canvas dengan CSS -->
                    <canvas id="statusChart" style="max-height: 300px;"></canvas> 
                    
                    <!-- List Status -->
                    <div class="mt-4">
                        @foreach($statusCounts as $status => $count)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $status }}</span>
                                <strong>{{ $count }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Notifikasi -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Notifikasi</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for notifications -->
                    <p>Fitur notifikasi dalam pengembangan.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Data untuk pie chart
const statusData = {
    labels: {!! json_encode(array_keys($statusCounts->toArray())) !!},
    datasets: [{
        data: {!! json_encode(array_values($statusCounts->toArray())) !!},
        backgroundColor: [
            '#FFCE56',
            '#FF6384',
            '#36A2EB'
        ]
    }]
};

// Debugging data
console.log(statusData);

// Konfigurasi chart
const config = {
    type: 'pie',
    data: statusData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const total = tooltipItem.dataset.data.reduce((acc, val) => acc + val, 0);
                        const currentValue = tooltipItem.raw;
                        const percentage = ((currentValue / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
                    }
                }
            }
        },
    }
};

// Render chart
window.onload = function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, config);
};
</script>

@endsection
