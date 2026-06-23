

<style>
    .details-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        margin-top: 20px;
    }
    .info-card {
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd;
        padding: 15px;
        border-radius: 4px;
    }
    .badge-present {
        background-color: #d1e7dd;
        color: #0f5132;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 4px;
    }
    .badge-absent {
        background-color: #f8d7da;
        color: #842029;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 4px;
    }
</style>

<?php $__env->startSection("content"); ?>
<div id="content">
    <div class="details-container">
        
        <div class="row mb-4">
            <div class="col-10">
                <h4><i class="bi bi-journal-text text-primary"></i> Attendance Log Details</h4>
            </div>
            <div class="col-2 text-end">
                <button onclick="window.close();" class="btn btn-secondary btn-sm"><i class="bi bi-x-circle"></i> Close Page</button>
            </div>
        </div>

        <div class="info-card mb-4">
            <div class="row">
                <div class="col-md-4">
                    <strong>Subject Name:</strong> <span class="text-muted"><?php echo e($subject->subjectName); ?></span>
                </div>
                <div class="col-md-4">
                    <strong>Subject Code:</strong> <span class="text-muted"><?php echo e($subject->subjectCode); ?></span>
                </div>
                <div class="col-md-4">
                    <strong>Lecturer/Teacher:</strong> <span class="text-muted"><?php echo e($subject->teacher_firstname ?? ''); ?> <?php echo e($subject->teacher_middlename ?? ''); ?> <?php echo e($subject->teacher_lastname ?? ''); ?></span>
                </div>
            </div>
            <?php if($startDate || $endDate): ?>
            <div class="row mt-2">
                <div class="col-md-12">
                    <small class="text-primary font-weight-bold">
                        <i class="bi bi-calendar3"></i> Period Filtered: From <?php echo e(\Carbon\Carbon::parse($startDate)->format('d M Y')); ?> to <?php echo e(\Carbon\Carbon::parse($endDate)->format('d M Y')); ?>

                    </small>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded text-center bg-light">
                    <h5 class="text-success mb-1"><i class="bi bi-check-circle-fill"></i> Total Present Session</h5>
                    <h3 class="fw-bold text-success"><?php echo e($attendances->where('status', 'present')->count()); ?></h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded text-center bg-light">
                    <h5 class="text-danger mb-1"><i class="bi bi-x-circle-fill"></i> Total Absent Session</h5>
                    <h3 class="fw-bold text-danger"><?php echo e($attendances->where('status', 'absent')->count()); ?></h3>
                </div>
            </div>
        </div>

        <div class="table-responsive">
           <table class="table table-bordered table-striped table-hover align-middle text-center">
    <thead class="table-dark">
        <tr>
            <th style="width: 8%;">S/N</th>
            <th>Date Logged</th>
            <th>Day of Week</th>
            <th>Time / Session</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $serialNumber = 1;
            // Tunagroup attendances kwa tarehe kuzuia tarehe moja kujirudia kwenye safu
            $groupedAttendances = $attendances->groupBy('date');
        ?>

        <?php $__empty_1 = true; $__currentLoopData = $groupedAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $dateAttendances): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $__currentLoopData = $dateAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    
                    <?php if($index === 0): ?>
                        <td rowspan="<?php echo e($dateAttendances->count()); ?>"><?php echo e($serialNumber++); ?></td>
                        <td rowspan="<?php echo e($dateAttendances->count()); ?>">
                            <?php echo e(\Carbon\Carbon::parse($date)->format('d-m-Y')); ?>

                        </td>
                        <td rowspan="<?php echo e($dateAttendances->count()); ?>">
                            <?php echo e(\Carbon\Carbon::parse($date)->format('l')); ?>

                        </td>
                    <?php endif; ?>

                    
                    <td>
                        <span class="badge bg-secondary">
                            <?php if(isset($attendance->start_time) && isset($attendance->end_time)): ?>
                                <?php echo e(\Carbon\Carbon::parse($attendance->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($attendance->end_time)->format('H:i')); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </span>
                    </td>

                    
                    <td>
                        <?php if(strtolower($attendance->status) == 'present'): ?>
                            <span class="badge bg-success text-white px-2 py-1 rounded" style="font-size: 0.85rem;">
                                <i class="bi bi-check2"></i> Present
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger text-white px-2 py-1 rounded" style="font-size: 0.85rem;">
                                <i class="bi bi-x"></i> Absent
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="5" class="text-center text-danger p-4">
                <i class="bi bi-exclamation-triangle fs-4"></i><br>
                No Attendance Log Found for This Subject within Specified Filter Criteria.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/details.blade.php ENDPATH**/ ?>