<?php $i = 1; ?>
<?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td><?php echo e($i++); ?></td>
    <td><?php echo e($teacher->firstname); ?> <?php echo e($teacher->middlename); ?> <?php echo e($teacher->lastname); ?></td>
    <td><?php echo e($teacher->gender); ?></td>

    <td><?php echo e($teacher->email); ?></td>
    <td><?php echo e($teacher->mobile); ?></td>
    <td><?php echo e($teacher->role); ?></td>
    <td>
        <?php if($teacher->status=="Blocked"): ?>
        <span class="badge bg-danger"><?php echo e($teacher->status); ?></span>
        <?php else: ?>
        <span class="badge bg-success"><?php echo e($teacher->status); ?></span>
        <?php endif; ?>
    </td>
    <td>
        <a href="<?php echo e(route('teachers.edit', $teacher->id)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>

        <form action="<?php echo e($teacher->status == 'Active' ? route('teachers.block', $teacher->id) : route('teachers.unblock', $teacher->id)); ?>" 
              method="POST" style="display:inline;" 
              onsubmit="return confirm('Are you sure you want to <?php echo e($teacher->status == 'Active' ? 'block' : 'unblock'); ?> this teacher?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <button class="btn btn-sm <?php echo e($teacher->status == 'Active' ? 'btn-outline-danger' : 'btn-outline-success'); ?>" 
                    title="<?php echo e($teacher->status == 'Active' ? 'Block' : 'Unblock'); ?>">
                <i class="bi <?php echo e($teacher->status == 'Active' ? 'bi-slash-circle-fill' : 'bi-check-circle-fill'); ?>"></i>
            </button>
        </form>
        <?php if($teacher->leave_status=="stop"): ?>

<button
class="btn btn-outline-info btn-sm viewLeaveBtn"
data-bs-toggle="modal"
data-bs-target="#viewLeaveModal"

data-leave="<?php echo e($teacher->leave_id); ?>"
data-id="<?php echo e($teacher->id); ?>"
data-name="<?php echo e($teacher->firstname); ?> <?php echo e($teacher->lastname); ?>"
data-reason="<?php echo e($teacher->leave_reason); ?>"
data-start="<?php echo e($teacher->start_date); ?>"
data-end="<?php echo e($teacher->end_date); ?>"
data-status="<?php echo e($teacher->leave_status); ?>"
>
    <i class="bi bi-eye"></i>
</button>

<?php else: ?>

<button
class="btn btn-outline-warning btn-sm leaveBtn"
data-bs-toggle="modal"
data-bs-target="#leaveModal"
data-id="<?php echo e($teacher->id); ?>"
data-name="<?php echo e($teacher->firstname); ?> <?php echo e($teacher->lastname); ?>"
>
<i class="bi bi-calendar-x"></i>
</button>

<?php endif; ?>
    
</button>
        <a href="<?php echo e(route('teacher.subjects',$teacher->id)); ?>" style="text-decoration: none;">View subjects</a>
        
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="9" class="text-center">No teachers found</td>
</tr>
<?php endif; ?>
<div class="modal fade" id="leaveModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="<?php echo e(route('teacher.exception.store')); ?>" method="POST">

                <?php echo csrf_field(); ?>

                <div class="modal-header">

                    <h5>Teacher Attendance Exception</h5>

                    <button class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="teacher_id"
                        id="teacher_id">

                    <div class="mb-3">

                        <label>Teacher</label>

                        <input
                            type="text"
                            id="teacher_name"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="mb-3">

                        <label>Reason</label>

                        <select
                            name="reason"
                            id="reason"
                            class="form-control"
                            required>

                            <option value="">Select</option>

                            <option value="Leave">
                                Leave
                            </option>

                            <option value="Syllabus Completed">
                                Syllabus Completed
                            </option>

                        </select>

                    </div>

                    <div id="leaveDates">

                        <div class="mb-3">

                            <label>Start Date</label>

                            <input
                                type="date"
                                name="start_date"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>End Date</label>

                            <input
                                type="date"
                                name="end_date"
                                class="form-control">

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-primary">

                        Save

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="viewLeaveModal">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST" action="<?php echo e(route('teacher.exception.update')); ?>">

<?php echo csrf_field(); ?>
<?php echo method_field('PUT'); ?>

<div class="modal-header">

<h5>Teacher Leave Details</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<input type="hidden" name="leave_id" id="v_leave_id">

<div class="mb-3">

<label>Teacher</label>

<input
type="text"
id="v_teacher"
class="form-control"
readonly>

</div>

<div class="mb-3">

<label>Reason</label>

<input
type="text"
id="v_reason"
class="form-control"
readonly>

</div>

<div class="mb-3">

<label>Start Date</label>

<input
type="text"
id="v_start"
class="form-control"
readonly>

</div>

<div class="mb-3">

<label>End Date</label>

<input
type="text"
id="v_end"
class="form-control"
readonly>

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="stop">Stop</option>

<option value="Continue">Continue</option>

</select>

</div>

</div>

<div class="modal-footer">

<button class="btn btn-success">

Update

</button>

</div>

</form>

</div>

</div>

</div>

<script>

document.addEventListener('click', function (e) {

    // ============================
    // LEAVE BUTTON
    // ============================
    const leaveBtn = e.target.closest('.leaveBtn');

    if (leaveBtn) {

        document.getElementById('teacher_id').value = leaveBtn.dataset.id;
        document.getElementById('teacher_name').value = leaveBtn.dataset.name;

    }

    // ============================
    // VIEW LEAVE BUTTON
    // ============================
    const viewBtn = e.target.closest('.viewLeaveBtn');

    if (viewBtn) {

        document.getElementById('v_leave_id').value = viewBtn.dataset.leave;
        document.getElementById('v_teacher').value = viewBtn.dataset.name;
        document.getElementById('v_reason').value = viewBtn.dataset.reason;
        document.getElementById('v_start').value = viewBtn.dataset.start;
        document.getElementById('v_end').value = viewBtn.dataset.end;

        const status = document.querySelector('#viewLeaveModal select[name="status"]');

        if (status) {
            status.value = viewBtn.dataset.status;
        }

    }

});

// ============================
// SHOW / HIDE LEAVE DATES
// ============================

const reason = document.getElementById('reason');
const leaveDates = document.getElementById('leaveDates');

if (leaveDates) {
    leaveDates.style.display = "none";
}

if (reason) {

    reason.addEventListener('change', function () {

        if (this.value === "Leave") {

            leaveDates.style.display = "block";

        } else {

            leaveDates.style.display = "none";

            const startDate = document.querySelector('[name="start_date"]');
            const endDate = document.querySelector('[name="end_date"]');

            if (startDate) startDate.value = '';
            if (endDate) endDate.value = '';

        }

    });

}

</script>
<script>

document.querySelectorAll('.leaveBtn').forEach(button=>{

    button.addEventListener('click',function(){

        document.getElementById('teacher_id').value=this.dataset.id;

        document.getElementById('teacher_name').value=this.dataset.name;

    });

});

const reason=document.getElementById('reason');

const leaveDates=document.getElementById('leaveDates');

leaveDates.style.display="none";

reason.addEventListener('change',function(){

    if(this.value=="Leave"){

        leaveDates.style.display="block";

    }

    else{

        leaveDates.style.display="none";

        document.querySelector('[name=start_date]').value='';

        document.querySelector('[name=end_date]').value='';

    }

});

</script>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/partials/teacher_table.blade.php ENDPATH**/ ?>