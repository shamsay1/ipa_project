<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Arial; }
h3 { text-align:center; color:green; }

table {
    width:100%;
    border-collapse: collapse;
    margin-bottom:40px;
}

th,td {
    border:1px solid #000;
    font-size:10px;
    padding:4px;
}

th {
    background:#0f2a44;
    color:#fff;
}

.teacher-box {
    page-break-after: always;
}
</style>
</head>
<body>

<?php $__currentLoopData = $allData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="teacher-box">

<h3>
TIMETABLE FOR TEACHER
<br>
<?php echo e($data['teacher']->firstname); ?> <?php echo e($data['teacher']->middlename); ?> <?php echo e($data['teacher']->lastname); ?>

</h3>

<table>
<thead>
<tr>
<th>DAY / TIME</th>

<?php $__currentLoopData = $data['timeslots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<th>
<?php echo e(date('H:i', strtotime($slot->start_time))); ?> - <?php echo e(date('H:i', strtotime($slot->end_time))); ?>

</th>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tr>
</thead>

<tbody>

<?php $__currentLoopData = $data['entries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $dayEntries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>
<td><b><?php echo e(strtoupper($day)); ?></b></td>

<?php $__currentLoopData = $data['timeslots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<td>

<?php
$found = $dayEntries
    ->where('start_time',$slot->start_time)
    ->where('end_time',$slot->end_time)
    ->first();
?>

<?php if($found): ?>

<?php if($found->group_name): ?>
<b><?php echo e($found->group_name); ?></b><br>
<?php endif; ?>

<?php echo e($found->subjectName); ?><br>
<?php echo e($found->fullCourseName); ?><br>
ROOM: <?php echo e($found->room_name); ?>


<?php endif; ?>

</td>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody>
</table>

</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/pdf2.blade.php ENDPATH**/ ?>