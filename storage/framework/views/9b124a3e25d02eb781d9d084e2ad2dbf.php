

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
            <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                <form action="<?php echo e(route('timetable.solveConflicts')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-success">
            Solve Crecit Hours Conflicts
        </button>
    </form>
    <form action="<?php echo e(route('timetable.solveConflicts1')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-info">
            Solve Other Conflicts
        </button>
    </form>
     
   <form action="<?php echo e(route('sync.group.subjects')); ?>" method="POST">
<?php echo csrf_field(); ?>

<button type="submit" class="btn btn-primary">
Sync Shared Subjects
</button>

</form>
        
    </div>


            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">conflicts Validation details</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
           <h3 style="text-align: center;font-family: 'Times New Roman', Times, serif;color: green">Timetable Validation Report</h3>
    <p><strong style="color: green">Overall Score: <span class="fw-bold"><?php echo e($score); ?>%</span></strong></p>
        <?php
        $i = 1;
    ?>
    <div class="text-center mt-4">
    




</div>

    <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule => $violations): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
        <div style="margin-bottom: 20px;">
            
            <h4 style="font-family: 'Times New Roman', Times, serif"><span><?php echo e($i++); ?>. </span> <?php echo e(ucfirst(str_replace('_', ' ', $rule))); ?></h4>
            <?php if(count($violations) === 0): ?>
                <p style="color: green;font-weight: bold;font-style: italic"> No conflicts</p>
            <?php else: ?>
                <p style="color: red;font-weight: bold;font-style: italic">Found <?php echo e(count($violations)); ?> conflicts</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php $__currentLoopData = (array) $violations[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($col); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $violations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php $__currentLoopData = (array) $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e($val); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/timetable/validation.blade.php ENDPATH**/ ?>