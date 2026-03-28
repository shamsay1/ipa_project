

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
        <div class="row mb-4">
            <div class="row align-items-center mb-4">
                <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                    

                   
                </div>

           
                <div class="col-md-5">
                    
                </div>
            </div>
        </div>
        <div class="container">

 

    <!-- Display Validation Errors -->
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul style="list-style: none">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    </div>
         <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Class represantative Information:  <?php echo e($todayName); ?></h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <!-- Table Section -->
      <div class="table-responsive">

<?php if(isset($timetable) && count($timetable) > 0): ?>

    <?php $__currentLoopData = $timetable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseName => $ntaLevels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        
        <div class="mb-5">
            <h3 class="text-center mb-4"
                style="color: green; font-family: 'Times New Roman', Times, serif;">
                TIME TABLE FOR <?php echo e(strtoupper($courseName)); ?>

            </h3>

            <?php $__currentLoopData = $ntaLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ntaLevel => $semesters): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                
                <h4 style="color: darkblue;
                           font-family: 'Times New Roman', Times, serif;
                           text-align: center;
                           margin-top: 25px;">
                    NTA <?php echo e($ntaLevel); ?>

                </h4>

                <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semesterName => $entries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    
                    <h5 style="color: darkred; text-align: center; margin-bottom: 10px;font-family: 'Times New Roman', Times, serif">
                        Semester: <?php echo e($semesterName); ?>

                    </h5>

                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start - End</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Room</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $groupedByDay = $entries->groupBy('day');
                        ?>

                        <?php $__currentLoopData = $groupedByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayName => $entriesByDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php $__currentLoopData = $entriesByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>

                                    
                                    <?php if($key === 0): ?>
                                        <td rowspan="<?php echo e(count($entriesByDay)); ?>"
                                            style="writing-mode: vertical-lr;
                                                   text-align: center;
                                                   font-weight: bold;">
                                            <?php echo e($dayName); ?>

                                        </td>
                                    <?php endif; ?>

                                    <td>
                                        <?php echo e(date('H:i', strtotime($entry->start_time))); ?>

                                        -
                                        <?php echo e(date('H:i', strtotime($entry->end_time))); ?>

                                    </td>

                                    <td><?php echo e($entry->subjectName); ?></td>

                                    <td>
                                        <?php echo e($entry->firstname); ?>

                                        <?php echo e($entry->lastname); ?>

                                    </td>

                                    <td><?php echo e($entry->room_name); ?></td>

                                    <td>
                                        <?php if($entry->status == "present"): ?>
                                            <span style="color: green;">
                                                Already Taught
                                            </span>
                                        <?php else: ?>
                                            <span style="color: red;">
                                                Subject Not Taught
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>
    <p>No timetable for today.</p>
<?php endif; ?>

</div>

    </div>
</div>

<!-- JQuery for live search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/supervision.blade.php ENDPATH**/ ?>