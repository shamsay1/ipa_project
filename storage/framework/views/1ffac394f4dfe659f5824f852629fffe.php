

<style>
    /* ===== STYLES (same structure as teacher page) ===== */
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
    .btn-export {
        background-color: #0d6efd;
        color: white;
    }
    .btn-export:hover {
        background-color: #0b5ed7;
        color: white;
    }
    .flash-message {
        background-color: #d1e7dd;
        border-color: #badbcc;
        color: #0f5132;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<?php $__env->startSection("content"); ?>
<div id="content">
    <div class="table-container p-3">
        <div class="row mb-4">
            <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassroomModal">
                    <i class="bi bi-plus-lg me-2"></i>Assign Classroom
                </button>
                <form action="<?php echo e(route('cimport')); ?>" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="file" id="excelFile" accept=".xls,.xlsx" onchange="document.getElementById('importForm').submit();">
                    </form>
                    <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click();">
                        <i class="bi bi-upload me-2"></i>Import Excel
                    </button>
                    <a href="/export-course-rooms-template" class="btn btn-info text-white">
                        <i class="bi bi-download me-2"></i>Download Template
                    </a>
            </div>

            
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-dismissible fade show flash-message" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Success!</h6>
                        <p class="mb-0"><?php echo e(session('success')); ?></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>NTA Level</th>
                        <th>Total Students</th>
                        <th>Classroom</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
    
    $groupedClassrooms = $classrooms->groupBy(function ($classroom) {
  
        return $classroom->course->courseName ?? 'Kozi Isiyojulikana';
    });
?>

<tbody>
    <?php
        $groupedClassrooms = $classrooms->groupBy('course_id');
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $groupedClassrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseId => $courseClassrooms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr class="accordion-header" data-bs-toggle="collapse" data-bs-target="#course-<?php echo e($courseId); ?>" style="cursor: pointer; background-color: #f8f9fa;">
            <td colspan="7" class="fw-bold">
                <i class="bi bi-chevron-down accordion-icon"></i>
                <?php echo e($courseClassrooms->first()->course->courseName ?? 'N/A'); ?>

                <span class="badge bg-secondary ms-2"><?php echo e($courseClassrooms->count()); ?> NTA levels</span>
            </td>
        </tr>

        <?php $__currentLoopData = $courseClassrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="collapse accordion-body" id="course-<?php echo e($courseId); ?>">
                <td><?php echo e($loop->parent->index * 100 + $index + 1); ?></td>
                <td class="text-muted">
                    <i class="bi bi-arrow-return-right me-2"></i>
                    <?php echo e($classroom->course->courseName ?? 'N/A'); ?>

                </td>
                <td><?php echo e($classroom->nta_level); ?></td>
                <td><?php echo e($classroom->total_students); ?></td>
                <td><?php echo e($classroom->room->name ?? 'N/A'); ?></td>
                <td><?php echo e($classroom->created_at->format('Y-m-d')); ?></td>
                <td>
                    <form action="<?php echo e(route('course-classrooms.destroy', $classroom->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="7" class="text-center text-muted">No Records Found</td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Adding Classroom -->
<div class="modal fade" id="addClassroomModal" tabindex="-1" aria-labelledby="addClassroomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white fw-bold" id="addClassroomModalLabel">Assign Classroom</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="<?php echo e(route('course-classrooms.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Course</label>
                    <select class="form-select" name="course_id" required>
                        <option value="">-- Select Course --</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($course->id); ?>"><?php echo e($course->courseName ?? $course->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">NTA Level</label>
                    <select name="nta_level" class="form-select">
                        <option value="">Select --NTA--</option>
                        <option value="NTA-4">NTA-4</option>
                        <option value="NTA-5">NTA-5</option>
                        <option value="NTA-6">NTA-6</option>
                        <option value="NTA-7">NTA-7</option>
                        <option value="NTA-8">NTA-8</option>
                        <option value="NTA-9">NTA-9</option>
                        <option value="NTA-10">NTA-10</option>
                    </select>
                </div>
            </div>

            <div class="row">
               
                <div class="col-md-6 mb-3">
                    <label class="form-label">Classroom</label>
                    <select class="form-select" name="room_id" required>
                        <option value="">-- Select Room --</option>
                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($room->id); ?>"><?php echo e($room->roomName ?? $room->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Students</label>
                    <input type="number" name="total_students" class="form-control">
                    
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/roomasign.blade.php ENDPATH**/ ?>