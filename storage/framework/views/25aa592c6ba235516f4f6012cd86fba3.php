

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


            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1"> Edit Teachers Information</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

<form action="<?php echo e(route('teachers.update', $teacher->id)); ?>" method="post">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="firstname" class="form-label">First name</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo e($teacher->firstname); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Middle Name</label>
                    <input type="text" id="email" name="middlename" class="form-control" value="<?php echo e($teacher->middlename); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lastname" class="form-label">Last name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo e($teacher->lastname); ?>">
                </div>

                
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control" value="<?php echo e($teacher->email); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="firstname" class="form-label">Phone Number</label>
                    <input type="text" id="firstname" name="mobile" class="form-control" value="<?php echo e($teacher->mobile); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lastname" class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="Male" <?php echo e($teacher->gender == "Male" ? "selected" : ""); ?>>Male</option>
                        <option value="Female" <?php echo e($teacher->gender == "Female" ? "selected" : ""); ?>>Female</option>
                    </select>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Teachers Code</label>
                    <input type="text" id="email" name="teacher_code" class="form-control" value="<?php echo e($teacher->teacher_code); ?>">
                </div>
                <div class="col-md-4 mb-3">
    <label for="department" class="form-label">Role</label>
    <select name="role" id="department" class="form-control" required>
                 <option value="teacher" <?php echo e($teacher->role == "teacher" ? "selected" : ""); ?>>Teacher</option>
                <option value="Supervisor" <?php echo e($teacher->role == "Supervisor" ? "selected" : ""); ?>>Supervisor</option>
                <option value="naibu" <?php echo e($teacher->role == "naibu" ? "selected" : ""); ?>>Deputy</option>
    </select>
</div>
                <div class="col-md-4 mb-3">
    <label for="department" class="form-label">Department</label>
    <select name="deptId" id="department" class="form-control" required>
        <option value="<?php echo e($teacher->deptId); ?>" selected>
            <?php echo e($teacher->department->deptName ?? 'Unknown Department'); ?>

        </option>
    </select>
</div>

            
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save me-2"></i>Update
                </button>
            </div>
        </form>
            

            
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/teacherEdit.blade.php ENDPATH**/ ?>