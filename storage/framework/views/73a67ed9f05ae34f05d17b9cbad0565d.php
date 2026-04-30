;

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
                    <form action="<?php echo e(route('teacher.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="teacher_file" id="excelFile" accept=".xls,.xlsx" onchange="document.getElementById('importForm').submit();">
                    </form>
                    <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click();">
                        <i class="bi bi-upload me-2"></i>Import Excel
                    </button>

                    <a href="<?php echo e(route('teacher.template')); ?>" class="btn btn-info text-white">
                        <i class="bi bi-download me-2"></i>Download Template
                    </a>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="bi bi-plus-lg me-2"></i>Add New Teacher
                    </button>
                </div>

                <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white fw-bold" id="addTeacherModalLabel">Add New Teacher</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="<?php echo e(route('teachers.store')); ?>" method="POST">
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
                    <label for="mobile" class="form-label">Teachers Code</label>
                    <input type="text" class="form-control" name="teacher_code" placeholder="Eg T002,T003" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" name="gender" required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
    <label for="department" class="form-label">Teacher Department</label>
    <select name="deptId" id="department" class="form-control" required>
        <option value="">-- Select Department --</option>
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($department->id); ?>"><?php echo e($department->deptName); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

            </div>
        </div>
        
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Teacher</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
      
    </div>
  </div>
</div>

                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search teachers..." autocomplete="off">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
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
      <h6 class="alert-heading mb-1">Teachers Information</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <!-- Table Section -->
        <div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>T/N</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Phone Number</th>
                
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="teacherTableBody">
            <?php echo $__env->make('partials.teacher_table', ['teachers' => $teachers], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </tbody>
    </table>

    <div class="mt-3" id="paginationLinks">
        <?php echo e($teachers->links()); ?>

    </div>
</div>

    </div>
</div>

<!-- JQuery for live search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val();

        $.ajax({
            url: "<?php echo e(route('teachers.index')); ?>",
            type: "GET",
            data: { search: query },
            beforeSend: function() {
                $('#teacherTableBody').html('<tr><td colspan="9" class="text-center"><div class="spinner-border text-primary" role="status"></div> Searching...</td></tr>');
            },
            success: function(data) {
                $('#teacherTableBody').html(data);
            },
            error: function() {
                $('#teacherTableBody').html('<tr><td colspan="9" class="text-danger text-center">Error loading data.</td></tr>');
            }
        });
    });
});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/teacher.blade.php ENDPATH**/ ?>