<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Timetable Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial, Helvetica, sans-serif;
}

body{
background:#f8fafc;
color:#1e293b;
}

/* PAGE STRUCTURE */

.page-wrapper{
min-height:100vh;
display:flex;
flex-direction:column;
}

.page-content{
flex:1;
}

/* NAVBAR */

.navbar{
background:#0f2a44;
border-bottom:4px solid #c9a227;
}

.navbar-brand{
font-weight:bold;
}

.nav-link{
color:white !important;
}

.nav-link:hover{
color:#c9a227 !important;
}

/* TABLE */

.table-scroll{
overflow-x:auto;
background:#fff;
border-radius:8px;
padding:15px;
}

table{
min-width:700px;
}

/* FOOTER */

footer{
background:#0f2a44;
color:#e5e7eb;
text-align:center;
padding:15px;
margin-top:auto;
}

/* MOBILE */

@media(max-width:768px){

table{
font-size:13px;
}

}

</style>
</head>

<body>

<div class="page-wrapper">

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container-fluid">

<a class="navbar-brand" href="#">
Timetable Management System
</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarMenu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarMenu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/studentDash')); ?>">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/studentSub')); ?>">Subjects</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/studenttbl')); ?>">Timetable</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/lessons')); ?>">Lessons</a>
</li>
<li class="nav-item">
<a class="nav-link" href="<?php echo e(route('studentprofile')); ?>">Settings</a>
</li>

<li class="nav-item">
<form method="POST" action="<?php echo e(route('logout')); ?>">
<?php echo csrf_field(); ?>
<button class="btn btn-warning ms-lg-3 mt-2 mt-lg-0">
Logout
</button>
</form>
</li>

</ul>

</div>
</div>

</nav>

<div class="page-content">

<section class="container mt-5">

<div class="table-scroll shadow">

<div class="text-center mb-3">
<img src="<?php echo e(asset('images/ipalogo1.png')); ?>" width="120">
</div>

<h3 class="text-center mb-4">
INSTITUTE OF PUBLIC AND ADMINISTRATION
</h3>

<div class="table-responsive">

    <?php if(isset($holidayMessage)): ?>
    <div class="alert alert-warning text-center">
        <?php echo e($holidayMessage); ?>

    </div>
    <?php else: ?>
       <table class="table table-hover table-bordered">

    <thead>

        <tr style="background:#0f2a44;color:white">
            <th colspan="100" class="text-center">Today's Lessons</th>
        </tr>

        <tr>
            <th>T/N</th>
            <th>Subject</th>
            <th>Code</th>
            <th>Timeslot</th>
            <th>Room</th>
            <th>Teacher</th>
            <th>Status</th>
            <th>Action</th>
            <th>Emergency</th>
        </tr>

    </thead>

    <tbody>

<?php $i = 1; ?>

<?php $__empty_1 = true; $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

<tr>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>"><?php echo e($i++); ?></td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>">
        <?php echo e($lesson->subjectName); ?>


        <?php if($lesson->leave_status=='stop'): ?>
            <br>
            <small class="text-white fw-bold">
                Somo limesimamishwa
            </small>

            <?php if($lesson->reason=="Leave"): ?>
                <br>
                <small class="text-white fw-bold">Mwalimu yuko likizo/semina</small>
            <?php elseif($lesson->reason=="Syllabus Completed"): ?>
               <br>
                <small>Mwalimu amemaliza Topics(Syllabus)</small>
            <?php endif; ?>
        <?php endif; ?>

    </td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>"><?php echo e($lesson->subjectCode); ?></td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>"><?php echo e($lesson->start_time); ?> - <?php echo e($lesson->end_time); ?></td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>"><?php echo e($lesson->room_name ?? 'N/A'); ?></td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>">
        <?php echo e($lesson->firstname); ?>

        <?php echo e($lesson->middlename); ?>

        <?php echo e($lesson->lastname); ?>

    </td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>">

        <?php if($lesson->leave_status=='stop'): ?>

            <span class="badge bg-dark">Lesson Suspended</span>

        <?php elseif($lesson->status=='present'): ?>

            <span class="badge bg-success">Present</span>

        <?php elseif($lesson->status=='emergency'): ?>

            <span class="badge bg-info">Emergency</span>

        <?php else: ?>

            <span class="badge bg-danger">Absent</span>

        <?php endif; ?>

    </td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>">

        <?php if($lesson->leave_status=='stop'): ?>

            <button class="btn btn-secondary btn-sm" disabled>
                Suspended
            </button>

        <?php elseif($lesson->status=='absent'): ?>

            <button
                class="btn btn-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#confirmPresentModal"
                data-id="<?php echo e($lesson->timetable_id); ?>">
                Attend
            </button>

        <?php else: ?>

            <button class="btn btn-success btn-sm" disabled>
                Present
            </button>

        <?php endif; ?>

    </td>

    <td class="<?php echo e(($lesson->leave_status == 'stop' || $lesson->leave_status == 'completed') ? 'bg-danger text-white' : ''); ?>">

        <?php if($lesson->leave_status=='stop'): ?>

            <button class="btn btn-secondary btn-sm" disabled>
                Suspended
            </button>

        <?php elseif($lesson->status=='present'): ?>

            <button class="btn btn-success btn-sm" disabled>
                Present
            </button>

        <?php else: ?>

            <button
                class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#emergencyModal"
                data-id="<?php echo e($lesson->timetable_id); ?>">
                Emergency
            </button>

        <?php endif; ?>

    </td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<tr>
    <td colspan="9" class="text-center">
        No lessons available
    </td>
</tr>

<?php endif; ?>

</tbody>

</table>
    <?php endif; ?>

</div>
<hr>

<div class="table-responsive mt-4">

    <h4 class="text-center" style="color:red; font-weight:bold;">
        Emergency Lessons (Pending Make-Up)
    </h4>

    <table class="table table-bordered table-hover">

        <thead style="background:#8b0000; color:white;">
            <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Code</th>
                <th>Teacher</th>
                <th>NTA</th>
                <th>Emergency Date</th>
                <th>Days Remaining</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php $__empty_1 = true; $__currentLoopData = $emergencyLessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php
                $daysPassed = (int) \Carbon\Carbon::parse($lesson->date)->diffInDays(now());
                $daysRemaining = 5 - $daysPassed;
            ?>

            <tr>

                <td><?php echo e($index + 1); ?></td>

                <td><?php echo e($lesson->subjectName); ?></td>

                <td><?php echo e($lesson->subjectCode ?? '-'); ?></td>

                <td>
                    <?php echo e($lesson->firstname); ?> <?php echo e($lesson->lastname); ?>

                </td>

               

                <td><?php echo e($lesson->nta_level); ?></td>

                <td>
                    <?php echo e(\Carbon\Carbon::parse($lesson->date)->format('d M Y')); ?>

                </td>

                <td>
                    <?php if($daysRemaining > 0): ?>
                        <span class="badge bg-warning text-dark">
                            <?php echo e($daysRemaining); ?> days left
                        </span>
                    
                    <?php endif; ?>
                </td>
                <td>
                    <button 
                        class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmPresentModal"
                        data-id="<?php echo e($lesson->timetable_id); ?>">
                        Mark Present
                    </button>
                </td>

            </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <tr>
                <td colspan="100%" class="text-center text-muted">
                    No emergency lessons for your course and NTA
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>


</div>

</section>

</div>


<div class="modal fade" id="emergencyModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Mark Emergency</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="<?php echo e(route('teacher.emergency')); ?>" method="POST">
<?php echo csrf_field(); ?>

<div class="modal-body">

<input type="hidden" name="timetable_id" id="emergency_timetable_id">


</div>

<div class="modal-footer">
<button type="submit" class="btn btn-warning">
Confirm Emergency
</button>
</div>

</form>

</div>
</div>
</div>


<!-- MODAL -->

<div class="modal fade" id="confirmPresentModal" tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST" action="<?php echo e(route('teacher.attendance.store')); ?>">

<?php echo csrf_field(); ?>

<input type="hidden" name="timetable_id" id="modal_timetable_id">

<div class="modal-header">

<h5 class="modal-title">Confirm Attendance</h5>

<button type="button" class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

Are you sure you want to mark this lesson as Present?

</div>

<div class="modal-footer">

<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
Cancel
</button>

<button type="submit" class="btn btn-success">
Confirm
</button>

</div>

</form>

</div>
</div>
</div>

<!-- FOOTER -->

<footer>

<p>&copy; 2025 ShamisTech. All Rights Reserved.</p>

</footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

var modal = document.getElementById('confirmPresentModal');

modal.addEventListener('show.bs.modal', function (event) {

var button = event.relatedTarget;

var id = button.getAttribute('data-id');

document.getElementById('modal_timetable_id').value = id;

});

});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    var emergencyModal = document.getElementById('emergencyModal');

    emergencyModal.addEventListener('show.bs.modal', function (event) {

        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');

        document.getElementById('emergency_timetable_id').value = id;
    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(session('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '<?php echo e(session('success')); ?>',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/lessons.blade.php ENDPATH**/ ?>