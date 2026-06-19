

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
</style>
---
<?php $__env->startSection("content"); ?>
<div id="content">
    <div class="table-container p-3">
        <div class="table-container p-3">
            <div class="row mb-4">
    <div class="row align-items-center mb-4">
    <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
        <form action="<?php echo e(route('course.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
            <?php echo csrf_field(); ?>
            <input type="file" name="course_file" id="excelFile" accept=".xls,.xlsx" onchange="document.getElementById('importForm').submit();">
        </form>
        <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click();">
            <i class="bi bi-upload me-2"></i>Import Excel
        </button>

        <a href="<?php echo e(route('course.template')); ?>" class="btn btn-info text-white">
            <i class="bi bi-download me-2"></i>Download Template
        </a>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Course
        </button>
    </div>

   
</div>
</div>

<!-- Modal for Adding Course -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white fw-bold" id="addTeacherModalLabel">Add New Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="<?php echo e(route('course.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="firstname" class="form-label">Course Name</label>
                                    <input type="text" class="form-control" name="courseName" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="firstname" class="form-label">Course Code</label>
                                    <input type="text" class="form-control" name="courseCode" placeholder="Eg C001,C002" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Department</label>
                                    <select class="form-select" name="deptId" required>
                                        <option value="">-- Select Department --</option>
                                        <?php $__currentLoopData = $depts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->deptName); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                             <div class="row mt-3">
                                
                                <div class="col-md-6">
                                    <label class="form-label">Course Level</label>
                                    <select class="form-select" name="course_level" required>
                                        <option value="">-- Select Course Level --</option>
                                        <option value="Diploma">Diploma Program</option>
                                        <option value="Degree">Degree Program</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Abbreviation</label>
                                    <input type="text" name="short_name" id="" class="form-control">
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
            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Course Information</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <div class="accordion" id="departmentAccordion">
    <?php $__currentLoopData = $deptCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?php echo e($dept->id); ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse<?php echo e($dept->id); ?>" aria-expanded="false"
                aria-controls="collapse<?php echo e($dept->id); ?>">
                Department: <?php echo e($dept->deptName); ?>

            </button>
        </h2>

        <div id="collapse<?php echo e($dept->id); ?>" class="accordion-collapse collapse"
            aria-labelledby="heading<?php echo e($dept->id); ?>" data-bs-parent="#departmentAccordion">
            <div class="accordion-body p-0">

                
                <h5 class="mt-3 px-3" style="text-align: center;font-family: 'Times New Roman', Times, serif">Degree Programs</h5>
                <?php if($dept->degree_courses->count() > 0): ?>
                <table class="table table-hover mb-4">
                    <thead>
                        <tr>
                            <th>C/N</th>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Abbreviation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dept->degree_courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($course->courseName); ?></td>
                            <td><?php echo e($course->course_code); ?></td>
                            <td><?php echo e($course->short_name); ?></td>
                            <td>
                                <a href="<?php echo e(route('course.edit', $course->id)); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="<?php echo e(route('course.destroy', $course->id)); ?>" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="px-3 text-muted">No Degree Courses Available</p>
                <?php endif; ?>

                <hr>

                
                <h5 class="px-3" style="text-align: center;font-family: 'Times New Roman', Times, serif">Diploma Programs</h5>
                <?php if($dept->diploma_courses->count() > 0): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>C/N</th>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Abbreviation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dept->diploma_courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($course->courseName); ?></td>
                            <td><?php echo e($course->course_code); ?></td>
                            <td><?php echo e($course->short_name); ?></td>
                            <td>
                                <a href="<?php echo e(route('course.edit', $course->id)); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="<?php echo e(route('course.destroy', $course->id)); ?>" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="px-3 text-muted">No Diploma Courses Available</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/course.blade.php ENDPATH**/ ?>