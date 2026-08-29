

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
      <h6 class="alert-heading mb-1">Subjects Performances</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <!-- Table Section -->
        <form method="GET" action="<?php echo e(route('report8')); ?>" class="row mb-4">

    <div class="col-md-4">

        <select class="form-control" name="department_id">

            <option value="">--- Select Department ---</option>

            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <option
                    value="<?php echo e($department->id); ?>"
                    <?php echo e($selectedDepartment==$department->id ? 'selected':''); ?>>

                    <?php echo e($department->deptName); ?>


                </option>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>

    </div>

    <div class="col-md-2">

        <button class="btn btn-primary">

            Filter

        </button>

    </div>

</form>
        <div class="table-responsive">




<?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

<div>

<div class="card-header bg-primary text-white p-1 text-center">

<b>

<?php echo e($teacher->firstname); ?>

<?php echo e($teacher->middlename); ?>

<?php echo e($teacher->lastname); ?>


</b>

</div>



<table class="table table-bordered table-striped">

<thead>

<tr>

<th>#</th>

<th>Subject</th>

<th>Code</th>

<th>Total Taught</th>

<th>Not Taught</th>

<th>Attendance %</th>

</tr>

</thead>

<tbody>

<?php

$i=1;

?>

<?php $__currentLoopData = $teacher->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>

<td><?php echo e($i++); ?></td>

<td><?php echo e($subject->subjectName); ?></td>

<td><?php echo e($subject->subjectCode); ?></td>

<td class="text-success">

<?php echo e($subject->total_taught); ?>


</td>

<td class="text-danger">

<?php echo e($subject->total_not_taught); ?>


</td>

<td>

<span class="badge bg-info">

<?php echo e($subject->percentage); ?> %

</span>

</td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<tr class="table-warning">

<td colspan="5">

<b>Overall Attendance Score</b>

</td>

<td>

<b>

<?php echo e($teacher->overall); ?> %

</b>

</td>

</tr>

</tbody>

</table>


</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<div class="alert alert-danger">

No Teacher Found

</div>

<?php endif; ?>

</div>
<div class="mt-3 nodis">
    <button onclick="printReport()" class="btn btn-success">
        Print Report
    </button>
</div>

    </div>
</div>

<script>

function formatDate(dateString)
{
    if(!dateString) return "ALL";

    let date = new Date(dateString);

    return date.toLocaleDateString('en-GB');
}

function printReport()
{

    const table = document.querySelector(".table-responsive").innerHTML;

    let department =
    "<?php echo e($selectedDepartment ? ($departments->where('id',$selectedDepartment)->first()->deptName ?? 'ALL DEPARTMENTS') : 'ALL DEPARTMENTS'); ?>";

    let today = new Date().toLocaleDateString('en-GB');

    let win = window.open('', '', 'width=1200,height=700');

    win.document.write(`
    <html>

    <head>

    <title>Teacher Attendance Report</title>

    <style>

    body{
        font-family:Arial;
        margin:20px;
    }

    h2,h3,h4{
        text-align:center;
        margin:3px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        margin-bottom:10px;
    }

    table th,
    table td{
        border:1px solid #000;
        padding:6px;
        text-align:center;
        font-size:13px;
    }

    .card{
        margin-bottom:30px;
    }

    </style>

    </head>

    <body>

    <h2>TEACHER ATTENDANCE REPORT</h2>

    <h4>Department : ${department}</h4>

    <h4>Printed : ${today}</h4>

    <hr>

    ${table}

    </body>

    </html>
    `);

    win.document.close();

    win.focus();

    win.print();

}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/report8.blade.php ENDPATH**/ ?>