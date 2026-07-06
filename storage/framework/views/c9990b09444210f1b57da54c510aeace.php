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

.timetable-table{
width:100%;
border-collapse:collapse;
min-width:700px;
}

.timetable-table th,
.timetable-table td{
border:1px solid #ddd;
padding:8px;
text-align:center;
font-size:14px;
vertical-align:middle;
}

.timetable-table th{
background:#0f2a44;
color:white;
}

/* SUBJECT STYLES */

.subject{
font-weight:bold;
font-size:13px;
}

.course{
font-size:12px;
color:#475569;
}

.teacher{
font-size:12px;
color:#334155;
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

.timetable-table{
font-size:12px;
}

.subject{
font-size:12px;
}

}

</style>
</head>

<body>

<div class="page-wrapper">

<!-- HEADER -->

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
  

<div id="printArea">

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Day</th>
            <th>Start - End</th>
            <th>Subject</th>
            <th>Teacher</th>
            <th>Room</th>
        </tr>
    </thead>

    <tbody>

    <?php $__currentLoopData = $timetableData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php $__currentLoopData = $timetable['entries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayName => $entriesByDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
                $totalRowsForDay = 0;

                foreach($entriesByDay as $entry){

                    $start = \Carbon\Carbon::parse($entry->start_time);
                    $end = \Carbon\Carbon::parse($entry->end_time);

                    $creditHour = $entry->credit_hour ?? 3;

                    $duration = $start->diffInHours($end);

                    $totalRowsForDay += min($duration,$creditHour);
                }

                $dayPrinted = false;
            ?>

            <?php $__currentLoopData = $entriesByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
                    $start = \Carbon\Carbon::parse($entry->start_time);
                    $end = \Carbon\Carbon::parse($entry->end_time);

                    $creditHour = $entry->credit_hour ?? 3;

                    $countHour = 0;
                ?>

                <?php while($start < $end && $countHour < $creditHour): ?>

                <tr>

                    <?php if(!$dayPrinted): ?>

                        <td rowspan="<?php echo e($totalRowsForDay); ?>"
                            style="writing-mode: vertical-lr;text-align:center;font-weight:bold">

                            <?php echo e($dayName); ?>


                        </td>

                        <?php $dayPrinted=true; ?>

                    <?php endif; ?>

                    <td>

                        <?php echo e($start->format('H:i')); ?>


                        -

                        <?php echo e($start->copy()->addHour()->format('H:i')); ?>


                    </td>

                    <td><?php echo e($entry->subjectName); ?></td>

                    <td>

                        <?php echo e($entry->firstname); ?>


                        <?php echo e($entry->middlename); ?>


                        <?php echo e($entry->lastname); ?>


                    </td>

                    <td><?php echo e($entry->room_name); ?></td>

                </tr>

                <?php
                    $start->addHour();
                    $countHour++;
                ?>

                <?php endwhile; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </tbody>

</table>

</div>
<div class="mt-3 text-end">

    <button class="btn btn-primary nodis"
        onclick="printTimetable(this)"
        data-course="<?php echo e($courseName ?? ''); ?>"
        data-nta="<?php echo e($ntaLevel ?? ''); ?>"
        data-group="<?php echo e($groupName ?? ''); ?>"
        data-semester="<?php echo e($semester ?? ''); ?>"
        data-active="<?php echo e($activeSemester ?? ''); ?>">

    <i class="bi bi-printer"></i>

    Print Timetable

</button>

</div>



</div>
</div>

</section>

</div>

<!-- FOOTER -->

<footer>
<p>&copy; 2025 ShamisTech. All Rights Reserved.</p>
</footer>

</div>

<!-- MODAL -->


<script>

function printTimetable(button){

    const courseName = button.getAttribute("data-course") || "";
    const ntaLevel  = button.getAttribute("data-nta") || "";
    const groupName = button.getAttribute("data-group") || "";
    const semester  = button.getAttribute("data-semester") || "";
    const active    = button.getAttribute("data-active") || "";

    const table = document.getElementById("printArea").innerHTML;

    const printWindow = window.open('', '', 'width=1200,height=900');

    printWindow.document.write(`

        <html>

        <head>

            <title>${courseName} Timetable</title>

            <style>

                @page{
                    size:A4 portrait;
                    margin:10mm;
                }

                body{
                    margin:25px;
                    background:#fff;
                    color:#000;
                    font-family:'Times New Roman',Times,serif;
                }

                h2,h4,h5{
                    text-align:center;
                    margin:2px 0;
                    line-height:1.5;
                }

                .date{
                    text-align:center;
                    font-style:italic;
                    margin-bottom:20px;
                }

                table{
                    width:100%;
                    border-collapse:collapse;
                    margin-top:20px;
                    font-size:13px;
                }

                th,
                td{
                    border:1px solid #000;
                    padding:7px;
                    text-align:center;
                    vertical-align:middle;
                }

                th{
                    background:#e9ecef;
                    font-weight:bold;
                    text-transform:uppercase;
                }

                tr:nth-child(even){
                    background:#f8f9fa;
                }

            </style>

        </head>

        <body onload="window.print();window.close();">

            <h2>THE INSTITUTE OF PUBLIC AND ADMINISTRATION</h2>

            <div class="date">

                Generated on

                ${new Date().toLocaleDateString('en-GB',{
                    day:'2-digit',
                    month:'long',
                    year:'numeric'
                })}

            </div>

            <h4>

                ${courseName.toUpperCase()}

                ${ntaLevel ? " - " + ntaLevel.toUpperCase() : ""}

            </h4>

            ${groupName
                ? `<h5>GROUP : ${groupName.toUpperCase()}</h5>`
                : ""
            }

            <h5>

                TIMETABLE

                ${semester ? "FOR " + semester.toUpperCase() : ""}

                ${active ? " : " + active.toUpperCase() : ""}

            </h5>

            ${table}

        </body>

        </html>

    `);

    printWindow.document.close();

}

</script>
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



</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/studenttbl.blade.php ENDPATH**/ ?>