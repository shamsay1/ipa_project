

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
                    <form action="<?php echo e(route('student.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="student_file" id="excelFile" accept=".xls,.xlsx" onchange="document.getElementById('importForm').submit();">
                    </form>
                    <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click();">
                        <i class="bi bi-upload me-2"></i>Import Excel
                    </button>

                    <a href="<?php echo e(route('student.template')); ?>" class="btn btn-info text-white">
                        <i class="bi bi-download me-2"></i>Download Template
                    </a>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="bi bi-plus-lg me-2"></i>Add New CR
                    </button>
                </div>

                <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white fw-bold" id="addTeacherModalLabel">Add New CR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="<?php echo e(route('cr_info.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lastname" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="middlename" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname" required>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" name="mobile" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" value="12345" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="" class="form-label">Semetser</label>
                    <select name="semester_id" class="form-control">
            <option value="">-- Select Semester --</option>
            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($semester->id); ?>" 
                    <?php echo e(($selectedSemester ?? '') == $semester->id ? 'selected' : ''); ?>>
                    <?php echo e($semester->semName); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="gender" class="form-label">Course</label>
                    <select class="form-select" name="course_id" required>
                        <option value="">-- Select Course --</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->courseName); ?></option>
                           
                            
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="gender" class="form-label">NTA</label>
                    <select name="nta" class="form-select">
        <option value="">-- Select NTA Level --</option>
        <option value="NTA-4">NTA-4</option>
        <option value="NTA-5">NTA-5</option>
        <option value="NTA-6">NTA-6</option>
        <option value="NTA-7">NTA-7</option>
        <option value="NTA-8">NTA-8</option>

        
      </select>
                </div>
              
            </div>
        </div>
        
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save CR</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
      
    </div>
  </div>
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
      <h6 class="alert-heading mb-1">Class represantative Information</h6>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <!-- Table Section -->
        <div class="table-responsive">
            <form method="GET" action="<?php echo e(url()->current()); ?>" class="mb-3 mt-2 p-2">
    <div class="input-group">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search name, email, mobile, course or NTA..."
               value="<?php echo e(request('search')); ?>">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Search
        </button>

        <a href="<?php echo e(url()->current()); ?>" class="btn btn-secondary">
            Reset
        </a>
    </div>
</form>
   <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Course</th>
                <th>NTA</th>
                <th>Semester</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $crs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <?php echo e($cr->firstname); ?>

                        <?php echo e($cr->middlename); ?>

                        <?php echo e($cr->lastname); ?>

                    </td>
                    <td><?php echo e($cr->mobile); ?></td>
                    <td><?php echo e($cr->email); ?></td>
                    <td><?php echo e($cr->course->courseName ?? ''); ?></td>
                    <td><?php echo e($cr->nta); ?></td>
                    <td><?php echo e($cr->semester->semName ?? 'N/A'); ?></td>
                    <td>
    <!-- Edit Button -->
    <button
        class="btn btn-sm btn-outline-primary editBtn"
        data-id="<?php echo e($cr->id); ?>"
        data-firstname="<?php echo e($cr->firstname); ?>"
        data-middlename="<?php echo e($cr->middlename); ?>"
        data-lastname="<?php echo e($cr->lastname); ?>"
        data-mobile="<?php echo e($cr->mobile); ?>"
        data-email="<?php echo e($cr->email); ?>"
        data-course="<?php echo e($cr->course_id); ?>"
        data-nta="<?php echo e($cr->nta); ?>"
        data-semester="<?php echo e($cr->semester_id); ?>"
        title="Edit">
        <i class="bi bi-pencil-square"></i>
    </button>

    <!-- Delete Button -->
    <form action="<?php echo e(route('cr_info.destroy', $cr->id)); ?>"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this student?')">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <button type="submit"
                class="btn btn-sm btn-outline-danger"
                title="Delete">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center">No Data Found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3" id="paginationLinks">
        <?php echo e($crs->links()); ?>

    </div>
</div>

    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Edit CR</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST" action="<?php echo e(route('cr.update','temp')); ?>" id="editForm">
<?php echo csrf_field(); ?>
<?php echo method_field('PUT'); ?>

<input type="hidden" name="id" id="edit_id">

<div class="modal-body">

<div class="container-fluid">

<!-- Row 1 -->
<div class="row mb-3">

<div class="col-md-4">
<label>First Name</label>
<input type="text" name="firstname" id="edit_firstname" class="form-control">
</div>

<div class="col-md-4">
<label>Middle Name</label>
<input type="text" name="middlename" id="edit_middlename" class="form-control">
</div>

<div class="col-md-4">
<label>Last Name</label>
<input type="text" name="lastname" id="edit_lastname" class="form-control">
</div>

</div>


<!-- Row 2 -->
<div class="row mb-3">

<div class="col-md-4">
<label>Mobile</label>
<input type="text" name="mobile" id="edit_mobile" class="form-control">
</div>

<div class="col-md-4">
<label>Email</label>
<input type="email" name="email" id="edit_email" class="form-control">
</div>

<div class="col-md-4">
<label>NTA Level</label>
<select name="nta" id="edit_nta" class="form-control">
<option value="NTA-4">NTA-4</option>
<option value="NTA-5">NTA-5</option>
<option value="NTA-6">NTA-6</option>
<option value="NTA-7">NTA-7</option>
<option value="NTA-8">NTA-8</option>
</select>
</div>

</div>


<!-- Row 3 -->
<div class="row mb-3">

<div class="col-md-6">
<label>Course</label>
<select name="course_id" id="edit_course" class="form-control">

<?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<option value="<?php echo e($course->id); ?>"><?php echo e($course->courseName); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</select>
</div>

<div class="col-md-6">
<label>Semester</label>
<select name="semester_id" id="edit_semester" class="form-control">

<?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<option value="<?php echo e($semester->id); ?>"><?php echo e($semester->semName); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</select>
</div>
<div class="col-md-12 mb-3">
    <label class="form-label">
        New Password <small class="text-muted">(Optional)</small>
    </label>
    <input type="password" name="password" class="form-control">
</div>

</div>

</div>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-success">Update</button>
</div>

</form>

</div>
</div>
</div>
<!-- JQuery for live search -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Handler kwa edit buttons
    document.querySelectorAll('.editBtn').forEach(button => {

        button.addEventListener('click', function() {
            // Chukua data zote kutoka button
            let id = this.dataset.id || '';
            let firstname = this.dataset.firstname || '';
            let middlename = this.dataset.middlename || '';
            let lastname = this.dataset.lastname || '';
            let mobile = this.dataset.mobile || '';
            let email = this.dataset.email || '';
            let course = this.dataset.course || '';
            let nta = this.dataset.nta || '';
            let semester = this.dataset.semester || '';

            // Set values kwenye modal inputs
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_firstname').value = firstname;
            document.getElementById('edit_middlename').value = middlename;
            document.getElementById('edit_lastname').value = lastname;
            document.getElementById('edit_mobile').value = mobile;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_course').value = course;
            document.getElementById('edit_nta').value = nta;
            document.getElementById('edit_semester').value = semester;

            // Update action ya form dynamically
            document.getElementById('editForm').action = "/cr-update/" + id;

            // Fungua modal
            let editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });

    });

});
</script>

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


<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/regcr.blade.php ENDPATH**/ ?>