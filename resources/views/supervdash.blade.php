@extends("layout.app")
<style>
    .table-container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            padding: 0.75rem;
        }
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
        }
        .progress {
            height: 8px;
        }
        .btn-export {
            background-color: #0d6efd;
            color: white;
        }
        .btn-export:hover {
            background-color: #0b5ed7;
            color: white;
        }
        .action-btn {
            color: #6c757d;
            padding: 0.25rem 0.5rem;
            font-size: 1.2rem;
        }
        .action-btn:hover {
            color: #0d6efd;
        }
        .flash-message {
    background-color: #d1e7dd; /* Light green background */
    border-color: #badbcc; /* Darker green border */
    color: #0f5132; /* Dark green text */
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-in-out;
}

.flash-message .alert-heading {
    color: #0f5132;
    font-weight: bold;
}

.flash-message .btn-close {
    color: #0f5132;
    opacity: 0.8;
}

.flash-message .bi-check-circle-fill {
    font-size: 1.5rem;
    color: #28a745; /* Deeper green for the icon */
}

/* Optional: Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
        
        /* Styles for submenu */
        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .submenu.show {
            max-height: 500px;
        }
        .submenu .nav-link {
            padding: 0.5rem 0.8rem 0.5rem 2rem;
            font-size: 0.85rem;
        }
        .has-submenu::after {
            content: '\f282';
            font-family: 'Bootstrap Icons';
            float: right;
            transition: transform 0.3s;
        }
        .has-submenu.collapsed::after {
            transform: rotate(-90deg);
        }
</style>
@section("content")
<div id="content">
        <div class="table-container p-3">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                
            </div>

            <!-- Content Row -->
            <div class="row mb-4">
                <!-- Total Teachers Card -->
                <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary dashboard-card h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary mb-1">
                        Total Session</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number">{{ $totalLessons }}</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-people card-icon text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success dashboard-card h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success mb-1">
                        Taught Lessons</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number">{{ $taughtLessons }}</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-book card-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info dashboard-card h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info mb-1">
                        Not Taught</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number">{{ $notTaughtLessons }}</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-building card-icon text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning dashboard-card h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        TOTAL DEPARTMENT</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number">4</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-building-fill-gear card-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    
    @if($showGraph)

<h5 class="mb-4 text-center">Today's Teaching Performance Per Course</h5>

<div style="width:100%; height:400px;">
    <canvas id="courseChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const labels = @json($labels);
    const taughtData = @json($taughtData);
    const notTaughtData = @json($notTaughtData);

    const ctx = document.getElementById('courseChart');

    if(ctx && labels.length > 0){

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Taught Lessons',
                        data: taughtData,
                        backgroundColor: 'green'
                    },
                    {
                        label: 'Not Taught Lessons',
                        data: notTaughtData,
                        backgroundColor: 'red'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio:false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

    } else {
        console.log("No graph data available");
    }

});
</script>

@else

<div class="alert alert-warning text-center mt-4">
    Today is a <strong>Non-Teaching Day</strong>. No teaching performance graph.
</div>

@endif


                </div>
            </div>

           </div>
        </div>
    </div>

@endsection