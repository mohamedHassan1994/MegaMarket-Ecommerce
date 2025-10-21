@extends('admin.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-4">

    {{-- KPI Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Total Orders</h6>
                        <h3 class="fw-bold mb-0">1,254</h3>
                    </div>
                    <div class="icon bg-primary text-white rounded-circle p-4">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Revenue</h6>
                        <h3 class="fw-bold mb-0">$45,230</h3>
                    </div>
                    <div class="icon bg-success text-white rounded-circle p-4">
                        <i class="fas fa-dollar-sign fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Customers</h6>
                        <h3 class="fw-bold mb-0">890</h3>
                    </div>
                    <div class="icon bg-warning text-white rounded-circle p-4">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Active Products</h6>
                        <h3 class="fw-bold mb-0">320</h3>
                    </div>
                    <div class="icon bg-info text-white rounded-circle p-4">
                        <i class="fas fa-box fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Sales Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Orders by Status</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                <canvas id="ordersStatusChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>


    {{-- Recent Orders --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Orders</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#1001</td>
                        <td>Mohamed Hassan</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>$220</td>
                        <td>2025-09-05</td>
                    </tr>
                    <tr>
                        <td>#1002</td>
                        <td>Ali Ahmed</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>$150</td>
                        <td>2025-09-06</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- New Customers --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">New Customers</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center">
                    <div class="avatar bg-primary text-white rounded-circle me-3 p-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Ahmed Ali</h6>
                        <small class="text-muted">Joined 2 days ago</small>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="avatar bg-success text-white rounded-circle me-3 p-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Sara Mohamed</h6>
                        <small class="text-muted">Joined yesterday</small>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="avatar bg-warning text-white rounded-circle me-3 p-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Omar Hassan</h6>
                        <small class="text-muted">Joined today</small>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Sales Overview Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
            datasets: [{
                label: 'Total Sales ($)',
                data: [1200, 1900, 3000, 2500, 3200, 4000, 4500, 2300, 4100, 8400, 6800, 7600],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Orders by Status Chart
const ordersCtx = document.getElementById('ordersStatusChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
        datasets: [{
            label: 'Orders',
            data: [65, 20, 15, 4],
            backgroundColor: [
                '#ffc107', // Pending
                '#0d6efd', // Processing
                '#198754', // Completed
                '#dc3545'  // Cancelled
            ],
            borderColor: '#fff',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',  // ⬅️ move labels to left
                labels: {
                    boxWidth: 18,
                    padding: 15,
                    font: { size: 14 }
                }
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        let label = context.label || '';
                        let value = context.raw || 0;
                        return `${label}: ${value} orders`;
                    }
                }
            },
            datalabels: {
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: function (value) {
                    return value; // shows numbers inside slices
                }
            }
        },
        cutout: '30%', // balanced hole size
    },
    plugins: [ChartDataLabels] // enable datalabels plugin
});


});
</script>
@endpush
