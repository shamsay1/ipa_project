<style>
/* NORMAL VIEW */
table {
    font-size: 14px;
}

/* PRINT MODE */
@media print {

    /* Ficha kila kitu */
    body * {
        visibility: hidden;
    }

    /* Onesha table tu */
    #mytable, #mytable * {
        visibility: visible;
    }

    #mytable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    table {
        font-size: 12px;
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 2px !important;
    }

    .title {
        font-size: 11px;
        margin: 3px;
    }

    td div {
        font-size: 12px;
        line-height: 1.1;
    }

    @page {
        size: A4 landscape;
        margin: 5mm;
    }
}
</style>

<?php $__env->startSection("content"); ?>

<div id="content">

    <!-- FILTER FORM -->
    <div class="table-container p-3 no-print">
        <form method="GET" action="">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Select Room</label>
                    <select name="room_id" class="form-control" required>
                        <option value="">-- Select Room --</option>
                        <?php $__currentLoopData = $allRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($room->id); ?>">
                                <?php echo e($room->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3 mt-4">
                    <button class="btn btn-primary mt-2">
                        Generate
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if($report && $report['selectedRoom']): ?>

    <!-- REPORT -->
    <div class="report-container" id="mytable">

        <h4 class="text-center title">
            INSTITUTE OF PUBLIC AND ADMINISTRATION
        </h4>

        <h4 class="text-center text-success title">
            Room Timetable (<?php echo e($report['selectedRoom']->name); ?>)
        </h4>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Day / Time</th>

                    <?php $__currentLoopData = $timeslots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th>
                            <?php echo e($slot->start_time); ?> <br> - <br> <?php echo e($slot->end_time); ?>

                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:bold;">
                        <?php echo e($day->day_name); ?>

                    </td>

                    <?php $__currentLoopData = $timeslots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                            $data = $report['usageMap'][$day->id][$slot->id] ?? null;
                        ?>

                        <td style="min-width:120px;">

                            <?php if($data): ?>
                                <div>
                                    <strong style="color:#0d6efd;">
                                        <?php echo e($data['teacher']); ?>

                                    </strong>
                                    <br>

                                    <span style="color:#198754;">
                                        <?php echo e($data['subject']); ?>

                                    </span>

                                    <?php if($data['course']): ?>
                                        <br>
                                        <small style="color:#6c757d;">
                                            <?php echo e($data['course']); ?>

                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    Free
                                </span>
                            <?php endif; ?>

                        </td>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </div>

    <?php endif; ?>

    <!-- PRINT BUTTON -->
    <div class="mt-3 text-center no-print">
        <button class="btn btn-success" onclick="myPrint()">
            Print Report
        </button>
    </div>

</div>

<script>
function myPrint() {
    window.print();
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/roomreport.blade.php ENDPATH**/ ?>