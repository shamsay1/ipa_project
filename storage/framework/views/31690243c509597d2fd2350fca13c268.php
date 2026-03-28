
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
<?php $__env->startSection("content"); ?>
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
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Teachers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number"><?php echo e($tcount); ?></div>
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
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Total Courses</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number"><?php echo e($total); ?></div>
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
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Classrooms</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number"><?php echo e($troom); ?></div>
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
                    <div class="h5 mb-0 font-weight-bold text-gray-800 stat-number"><?php echo e($dept1); ?></div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-building-fill-gear card-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>

            <!-- Content Row -->
            <div class="row mb-4">
                <!-- Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="chart-container">
                        <h5 class="card-title mb-4" style="font-family: 'Times New Roman', Times, serif;font-size: 28px;">Academic Year :
                            <?php if($activeSemester): ?>
                            <span style="color: green">
                             ( <?php echo e($activeSemester->academic_year); ?> )
                        </span>
                         <?php else: ?>
                          <span style="color: red">No Active semester</span>
                            <?php endif; ?>
                        </h5>
                        <div class="container">
                            <h3 class="mb-4" style="font-family: 'Times New Roman', Times, serif;text-align: center;color: green">Bar Graph: Periods per day</h3>
                            <canvas id="periodsChart" width="400" height="200"></canvas>
                        </div>
                        <!-- ✅ Chart.js (latest stable - v4.4.5) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.5/dist/chart.umd.min.js" integrity="" crossorigin="anonymous"></script>
<script>
    const ctx = document.getElementById('periodsChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels, 15, 512) ?>,
            datasets: [{
                label: 'Number of periods',
                data: <?php echo json_encode($periods, 15, 512) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="col-xl-4 col-lg-5">
                    <div class="recent-activities">
                        <h5 class="card-title mb-4">Recent Activities</h5>
                        <?php $__currentLoopData = $all_logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="activity-item">
                            <h6 class="mb-1"><?php echo e($log->title); ?></h6>
                            <p class="mb-0"><?php echo e($log->action); ?></p>
                            <div class="activity-time"><?php echo e($log->created_at->diffForHumans()); ?></div>
                        </div>
                            
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                       
                        
                        <div class="activity-time" style="text-align: center">
                            <a href="/logs">View all</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/dashboard.blade.php ENDPATH**/ ?>