@extends('main.main')

@section('main')
<div class="container mt-5">
    <!-- 4 Box Model -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-router text-primary display-4"></i>
                    <h5 class="mt-2">Router</h5>
                    <h3>{{ $modelCounts['Router'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-shield-lock text-danger display-4"></i>
                    <h5 class="mt-2">Firewall</h5>
                    <h3>{{ $modelCounts['Firewall'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-wifi text-success display-4"></i>
                    <h5 class="mt-2">Access Point</h5>
                    <h3>{{ $modelCounts['Access Point'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-usb-plug text-warning display-4"></i>
                    <h5 class="mt-2">Accessories</h5>
                    <h3>{{ $modelCounts['Accessories'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
<!-- Bagian Tengah -->
<div class="row mb-4">
    <!-- Diagram dan List Status dalam satu box -->
    <div class="col-md-8">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-gradient-primary d-flex justify-content-between align-items-center">
                <h5 class="text-center mb-0">Asset Summary</h5>
                </div>
            <div class="card-body d-flex flex-wrap" style="height: auto;">
                <!-- Diagram -->
                <div class="flex-grow-1 p-3" style="min-width: 300px;">
                    <canvas id="statusChart" style="width: 100%; height: 150%;"></canvas>
                </div>

                <!-- List Status di sebelah kanan -->
                <div class="flex-grow-1 p-3" style="min-width: 250px; max-width: 100%; background-color: #f1f4f6fa; border-left: 1px solid #e3e6f0; border-radius: 5px; overflow-y: auto;">
                    <h6 class="text-center font-weight-bold mb-3">Status Overview</h6>
                    <ul class="list-group">
                        @foreach($statusCounts as $status => $count)
                            @php
                                $percentage = ($count / array_sum($statusCounts->toArray()) * 100);
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $status }}
                                <span class="badge bg-primary rounded-pill">{{ number_format($percentage, 2) }}%</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

     <!-- Bagian Kanan: Notifikasi -->

     <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Notifications</h5> 
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @if($maintenanceModels->isNotEmpty())
                    <ul class="list-group">
                        @foreach($maintenanceModels as $model)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <strong>{{ $model->name }}</strong> 
                                    <small class="text-muted">under maintenance since {{ $model->created_at->format('d-m-Y H:i') }}</small>
                                </span>
                                <span class="badge bg-warning">Important</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center text-muted">No notifications.</p> 
                @endif
            </div>
        </div>
    </div>

    </div>
</div>
</div>

<!-- Chart.js -->
<script src="//cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Data untuk pie chart
const statusData = {
    labels: {!! json_encode(array_keys($statusCounts->toArray())) !!},
    datasets: [{
        data: {!! json_encode(array_values($statusCounts->toArray())) !!},
        backgroundColor: [
            '#FFCE56',
            '#FF6384',
            '#36A2EB',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40'
        ],
    }]
};

// Konfigurasi chart
const config = {
    type: 'pie',
    data: statusData,
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const total = tooltipItem.dataset.data.reduce((acc, val) => acc + val, 0);
                        const value = tooltipItem.raw;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    }
                }
            }
        },
        maintainAspectRatio: false,
    }
};

// Render chart
window.onload = function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, config);
};
</script>

@endsection
