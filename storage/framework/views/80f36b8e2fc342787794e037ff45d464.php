
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<?php $__env->startSection("content"); ?>
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
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div id="content">
    <div class="table-container p-3">
        <h3 style="text-align: center;font-family: 'Times New Roman', Times, serif;color: green;marging-bottom: 14px;">Update Subject Information: <?php echo e($subject->subjectName); ?></h3>
        
        <?php if(session('success')): ?>
        <div class="alert alert-dismissible fade show flash-message" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">Edit Subject Information</h6>
                    <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('subject.update', $subject->id)); ?>" method="post">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="subjectName" class="form-label">Subject Name</label>
                    <input type="text" id="subjectName" name="subjectName" class="form-control" value="<?php echo e($subject->subjectName); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="subjectCode" class="form-label">Subject Code</label>
                    <input type="text" id="subjectCode" name="subjectCode" class="form-control" value="<?php echo e($subject->subjectCode); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="teacher_id" class="form-label">Teacher Name</label><br>
                    <select name="teacher_id" class=" fomr-select selectpicker w-100" data-live-search="true" style="width:100%">
                        <?php $__currentLoopData = $teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $te): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($te->id); ?>" <?php echo e($subject->teacher_id == $te->id ? "selected" : ""); ?>>
                                <?php echo e($te->firstname); ?> <?php echo e($te->middlename); ?> <?php echo e($te->lastname); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="course_id" class="form-label">Course Name</label><br>
                    <select name="course_id" class="selectpicker w-100" data-live-search="true">
                        <?php $__currentLoopData = $course; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $co): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($co->id); ?>" <?php echo e($subject->course_id == $co->id ? "selected" : ""); ?>><?php echo e($co->courseName); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nta_level" class="form-label">NTA Level</label>
                    <select name="nta_level" class="form-select">
                        <option value="NTA-4" <?php echo e($subject->nta_level == "NTA-4" ? "selected" : ""); ?>>NTA-4</option>
                        <option value="NTA-5" <?php echo e($subject->nta_level == "NTA-5" ? "selected" : ""); ?>>NTA-5</option>
                        <option value="NTA-6" <?php echo e($subject->nta_level == "NTA-6" ? "selected" : ""); ?>>NTA-6</option>
                        <option value="NTA-7" <?php echo e($subject->nta_level == "NTA-7" ? "selected" : ""); ?>>NTA-7</option>
                        <option value="NTA-8" <?php echo e($subject->nta_level == "NTA-8" ? "selected" : ""); ?>>NTA-8</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="subject_type" class="form-label">Subject Type</label>
                    <select name="subject_type" class="form-select">

                        <option value="Theory" <?php echo e($subject->subject_type == "Theory" ? "selected" : ""); ?>>Theory</option>
                        <option value="Practical" <?php echo e($subject->subject_type == "Practical" ? "selected" : ""); ?>>Practical</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="required_lab" class="form-label">Required Lab</label><br>
                    <select name="required_lab" class="selectpicker w-100" data-live-search="true">
                        <option value="Theory" <?php echo e($subject->required_lab == "Theory" ? "selected" : ""); ?>>Theory</option>
                        <option value="Computer" <?php echo e($subject->required_lab == "Computer" ? "selected" : ""); ?>>Computer Lab</option>
                        <option value="Typing Room" <?php echo e($subject->required_lab == "Typing Room" ? "selected" : ""); ?>>Typing Room</option>
             
                    </select>
                </div>

                

                <div class="col-md-4 mb-3">
                    <label for="credit_hour" class="form-label">Credit Hours</label>
                    <input type="text" id="credit_hour" name="credit_hour" class="form-control" value="<?php echo e($subject->credit_hour); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="credit_hour" class="form-label">Shared Group</label>
                    <input type="text" id="credit_hour" name="group_name" class="form-control" value="<?php echo e($subject->group_name); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="credit_hour" class="form-label">Shared Group</label>
                    <input type="text" id="credit_hour" name="semester_id" class="form-control" value="<?php echo e($subject->semester_id); ?>">
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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function(){
    $('.selectpicker').selectpicker();
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/subjectEdit.blade.php ENDPATH**/ ?>