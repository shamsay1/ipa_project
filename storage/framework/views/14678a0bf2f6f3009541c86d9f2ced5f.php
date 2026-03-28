<?php $i = 1; ?>
<?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td><?php echo e($i++); ?></td>
    <td><?php echo e($teacher->firstname); ?> <?php echo e($teacher->middlename); ?> <?php echo e($teacher->lastname); ?></td>
    <td><?php echo e($teacher->gender); ?></td>

    <td><?php echo e($teacher->email); ?></td>
    <td><?php echo e($teacher->mobile); ?></td>
    <td><?php echo e($teacher->teacher_code); ?></td>
    <td><?php echo e($teacher->role); ?></td>
    <td><?php echo e($teacher->status); ?></td>
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
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="9" class="text-center">No teachers found</td>
</tr>
<?php endif; ?>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/partials/teacher_table.blade.php ENDPATH**/ ?>