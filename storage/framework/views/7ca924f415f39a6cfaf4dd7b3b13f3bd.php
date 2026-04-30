

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
        text-transform: uppercase;
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
        background-color: #d1e7dd;
        border-color: #badbcc;
        color: #0f5132;
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
        color: #28a745;
    }
    


    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .accordion-button:not(.collapsed) {
        color: #fff;
        background-color: #0d6efd;
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
    }
      .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px); /* Bootstrap's default input height */
            padding: 0.375rem 0.75rem; /* Bootstrap's default input padding */
            border: 1px solid #ced4da; /* Bootstrap's default input border */
            border-radius: 0.25rem; 
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px); /* Match height */
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: inherit; 
        }
        .select2-dropdown {
            border: 1px solid #ced4da; 
            border-radius: 0.25rem; 
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); 
        }
        .select2-search input {
            border: 1px solid #ced4da !important; 
            border-radius: 0.25rem !important;
            padding: 0.375rem 0.75rem !important;
        }
</style>

<?php $__env->startSection("content"); ?>
<div id="content">
    <div class="table-container p-3">
         <div class="row mb-4">
    <div class="row align-items-center mb-4">
    <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
        <form action="<?php echo e(route('subject.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
            <?php echo csrf_field(); ?>
            <input type="file" name="subject_file" id="excelFile" accept=".xls,.xlsx" onchange="document.getElementById('importForm').submit();">
        </form>
        <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click();">
            <i class="bi bi-upload me-2"></i>Import Excel
        </button>

        <a href="<?php echo e(route('subject.template')); ?>" class="btn btn-info text-white">
            <i class="bi bi-download me-2"></i>Download Template
        </a>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Subject
        </button>
    </div>

    <div class="container mt-3">

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
</div>
</div>

<!-- Modal for Adding Teacher -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary">
        <h5 class="modal-title fw-bold text-white" id="addTeacherModalLabel">Add New Subject</h5>
        <button type="button" class="btn-close bg-info" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      
      <form action="<?php echo e(route('subject.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="firstname" class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" name="subName" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="lastname" class="form-label">Subject Code</label>
                                    <input type="text" class="form-control" name="subCode" required>
                                </div>
                               <div class="col-md-4 mb-3">
    <label for="teacher" class="form-label">Teacher</label>
    <select class="form-select" name="teacher" id="teacher_id" required>
        <option value=""></option>
        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->firstname); ?> <?php echo e($teacher->lastname); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = bootstrap.Alert.getInstance(alert);
                if (bsAlert) {
                    bsAlert.hide();
                } else {
                    alert.remove();
                }
            });
        }, 5000);
        $('#teacher_id').select2({
            placeholder: "Search and select a teacher",
            allowClear: true 
        });
        var deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
          
            var button = event.relatedTarget;
            var classId = button.getAttribute('data-class-id');
            var className = button.getAttribute('data-class-name');

            var modalClassIdInput = deleteConfirmationModal.querySelector('#modalClassId');
            var modalClassNameStrong = deleteConfirmationModal.querySelector('#modalClassName');

            modalClassIdInput.value = classId;
            maximumSelectionLength: 3
            modalClassNameStrong.textContent = className;
        });
    });
</script>

                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Course</label>
                                    <select class="form-select" name="course" required>
                                        <option value="">-- Select Course --</option>
                                        <?php $__currentLoopData = $courses1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->courseName); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">NTA Level</label>
                                    <select class="form-select" name="nta" required>
                                        <option value="">-- Select NTA --</option>
                                        <option value="NTA-4">NTA-4</option>
                                        <option value="NTA-5">NTA-5</option>
                                        <option value="NTA-6">NTA-6</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="password" class="form-label">Semester</label>
                                    <select class="form-select" name="semester" required>
                                        <?php $__currentLoopData = $semester; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sem->id); ?>"><?php echo e($sem->semName); ?> <?php echo e($sem->academic_year); ?></option>
                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Subject Type</label>
                                    <select class="form-select" name="subject_type" required>
                                        <option value="">-- Select type --</option>
                                        <option value="Theory">Theory</option>
                                        <option value="Practical">Practical</option>
                                        
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Required Lab</label>
                                    <select class="form-select" name="required_lab" required>
                                        <option value="">-- Select --</option>
                                        <option value="Theory">Theory</option>
                                         <option value="Computer Lab">Computer Lab</option>
                                            <option value="Skill Lab">Skill Lab</option>
                                            <option value="Compounding Lab">Compounding Lab</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="lastname" class="form-label">Credit Hours</label>
                                    <input type="number" class="form-control" name="crhour" required>
                                </div>
                            </div>
                            
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
      
    </div>
  </div>
</div>
            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Subjects Information</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
        <div class="container">

<style>

.table-fixed{
table-layout: fixed;
width:100%;
}

.table-fixed th,
.table-fixed td{
white-space: normal;
word-wrap: break-word;
vertical-align: middle;
}

.col-id{width:60px;}
.col-name{width:220px;}
.col-code{width:120px;}
.col-credit{width:110px;}
.col-type{width:120px;}
.col-group{width:200px;}
.col-teacher{width:180px;}
.col-mobile{width:140px;}
.col-action{width:120px;}

</style>


<!-- ========================== -->
<!-- DEGREE COURSES SECTION -->
<!-- ========================== -->

<h4 class="mt-3 text-primary">Degree Courses</h4>

<div class="accordion" id="degreeAccordion">

<?php $__currentLoopData = $degreeCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="accordion-item">

<h2 class="accordion-header">
<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#course-deg-<?php echo e($course->id); ?>">

<?php echo e($course->courseName); ?>


</button>
</h2>


<div id="course-deg-<?php echo e($course->id); ?>"
class="accordion-collapse collapse"
data-bs-parent="#degreeAccordion">

<div class="accordion-body">

<?php
$subjectsByNta = $course->subjects->groupBy('nta_level');
?>


<?php $__currentLoopData = $subjectsByNta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta => $subjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#deg-nta-<?php echo e($course->id); ?>-<?php echo e($nta); ?>">

<?php echo e($nta); ?>


</button>

</h2>


<div id="deg-nta-<?php echo e($course->id); ?>-<?php echo e($nta); ?>"
class="accordion-collapse collapse">

<div class="accordion-body">

<?php
$subjectsBySemester = $subjects->groupBy(function($sub){
return $sub->semester->semName;
});
?>


<?php $__currentLoopData = $subjectsBySemester; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semesterName => $semesterSubjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<h6 class="text-success mt-3"><?php echo e($semesterName); ?></h6>

<div class="table-responsive">

        <table class="table table-bordered table-hover table-fixed">

        <thead class="table-light">

        <tr>

        <th class="col-id">#</th>
        <th class="col-name">Subject Name</th>
        <th class="col-code">Code</th>
        <th class="col-credit">Credit</th>
        <th class="col-type">Type</th>
        <th class="col-group">GROUP</th>
        <th class="col-teacher">Teacher</th>
        <th class="col-mobile">Mobile</th>
        <th class="col-action">Action</th>

        </tr>

        </thead>

        <tbody>

        <?php $__currentLoopData = $semesterSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <tr>

        <td><?php echo e($index+1); ?></td>

        <td><?php echo e($subject->subjectName); ?></td>

        <td><?php echo e($subject->subjectCode); ?></td>

        <td><?php echo e($subject->credit_hour); ?></td>

        <td><?php echo e($subject->subject_type); ?></td>
        <td><?php echo e($subject->group_name); ?></td>

        <td>

        <?php echo e($subject->teacher->firstname); ?>

        <?php echo e($subject->teacher->middlename); ?>

        <?php echo e($subject->teacher->lastname); ?>


        </td>

        <td><?php echo e($subject->teacher->mobile); ?></td>

        <td>

        <a href="<?php echo e(route('subject.edit',$subject->id)); ?>"
        class="btn btn-sm btn-outline-primary">

        <i class="bi bi-pencil-square"></i>

        </a>


        <form action="<?php echo e(route('subject.destroy',$subject->id)); ?>"
        method="POST"
        style="display:inline"
        onsubmit="return confirm('Delete subject?')">

        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <button class="btn btn-sm btn-outline-danger">

        <i class="bi bi-trash"></i>

        </button>

        </form>

        </td>

        </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>

        </table>

</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</div>
</div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
</div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>


<!-- ========================== -->
<!-- DIPLOMA COURSES SECTION -->
<!-- ========================== -->

<h4 class="mt-5 text-success">Diploma Courses</h4>

<div class="accordion" id="diplomaAccordion">

<?php $__currentLoopData = $diplomaCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#course-dip-<?php echo e($course->id); ?>">

<?php echo e($course->courseName); ?>


</button>

</h2>


<div id="course-dip-<?php echo e($course->id); ?>"
class="accordion-collapse collapse"
data-bs-parent="#diplomaAccordion">

<div class="accordion-body">

<?php
$subjectsByNta = $course->subjects->groupBy('nta_level');
?>


<?php $__currentLoopData = $subjectsByNta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta => $subjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#dip-nta-<?php echo e($course->id); ?>-<?php echo e($nta); ?>">

<?php echo e($nta); ?>


</button>

</h2>


<div id="dip-nta-<?php echo e($course->id); ?>-<?php echo e($nta); ?>"
class="accordion-collapse collapse">

<div class="accordion-body">

<?php
$subjectsBySemester = $subjects->groupBy(function($sub){
return $sub->semester->semName;
});
?>


<?php $__currentLoopData = $subjectsBySemester; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semesterName => $semesterSubjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<h6 class="text-success mt-3"><?php echo e($semesterName); ?></h6>

<div class="table-responsive">

<table class="table table-bordered table-hover table-fixed">

<thead class="table-light">

<tr>

<th class="col-id">#</th>
<th class="col-name">Subject Name</th>
<th class="col-code">Code</th>
<th class="col-credit">Credit</th>
<th class="col-type">Type</th>
<th class="col-group">Group</th>
<th class="col-teacher">Teacher</th>
<th class="col-mobile">Mobile</th>
<th class="col-action">Action</th>

</tr>

</thead>

<tbody>

<?php $__currentLoopData = $semesterSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>

<td><?php echo e($index+1); ?></td>

<td><?php echo e($subject->subjectName); ?></td>

<td><?php echo e($subject->subjectCode); ?></td>

<td><?php echo e($subject->credit_hour); ?></td>

<td><?php echo e($subject->subject_type); ?></td>
<td><?php echo e($subject->group_name); ?></td>

<td>

<?php echo e($subject->teacher->firstname); ?>

<?php echo e($subject->teacher->middlename); ?>

<?php echo e($subject->teacher->lastname); ?>


</td>

<td><?php echo e($subject->teacher->mobile); ?></td>

<td>

<a href="<?php echo e(route('subject.edit',$subject->id)); ?>"
class="btn btn-sm btn-outline-primary">

<i class="bi bi-pencil-square"></i>

</a>

<form action="<?php echo e(route('subject.destroy',$subject->id)); ?>"
method="POST"
style="display:inline"
onsubmit="return confirm('Delete subject?')">

<?php echo csrf_field(); ?>
<?php echo method_field('DELETE'); ?>

<button class="btn btn-sm btn-outline-danger">

<i class="bi bi-trash"></i>

</button>

</form>

</td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody>

</table>

</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
</div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
</div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

</div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/subject.blade.php ENDPATH**/ ?>