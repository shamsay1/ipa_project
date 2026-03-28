

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
            <h3></h3>
        <h5 style="font-family: 'Times New Roman', Times, serif;text-align: center;font-size: 30px">Teaching Timetable for Teacher <strong style="font-family: 'Times New Roman', Times, serif;text-align: center;color: green;font-size: 25px"><?php echo e(strtoupper($teacher->firstname . ' ' . $teacher->lastname)); ?></strong></h5>
        <?php if($activeSemester): ?>
            <p style="font-family: 'Times New Roman', Times, serif;text-align: center;color: green;font-size: 30px"><strong>Semester:</strong> <?php echo e($activeSemester->semName); ?></p>
        <?php endif; ?>
        
        <table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Day</th>
            <th>Start - End</th>
            <th>Subject</th>
            <th>Course</th>
            <th>Room</th>
        </tr>
    </thead>

    <tbody>
<?php $__empty_1 = true; $__currentLoopData = $groupedEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $entries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>

            <?php if($key === 0): ?>
                <td rowspan="<?php echo e(count($entries)); ?>" 
                    class="align-middle" 
                    style="writing-mode: vertical-lr; text-align:center; font-weight:bolder">
                    <strong><?php echo e($day); ?></strong>
                </td>
            <?php endif; ?>

            
            <td><?php echo e(date('H:i', strtotime($entry->start_time))); ?> - <?php echo e(date('H:i', strtotime($entry->end_time))); ?></td>

            
            <td><?php echo e($entry->subjectName); ?></td>

            
            <td>
                <?php echo e($entry->courseName . ' : ' . $entry->nta_level); ?>

                <?php if($entry->group_name): ?>
                    <?php echo e(' GROUP ' . strtoupper($entry->group_name)); ?>

                <?php endif; ?>
            </td>

            
            <td><?php echo e($entry->room_name); ?></td>

        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="6">No timetable found for this teacher.</td>
    </tr>
<?php endif; ?>
</tbody>

</table>


</div>
        <a href="<?php echo e(route('teacher.load.report1')); ?>" class="btn btn-secondary btn-sm">← Back</a>





<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/viewttimetable.blade.php ENDPATH**/ ?>