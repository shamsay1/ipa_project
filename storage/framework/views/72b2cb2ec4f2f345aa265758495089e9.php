<?php $i = 1; ?>

<?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td><?php echo e($i++); ?></td>
    <td><?php echo e($room->name); ?></td>
    <td><?php echo e($room->capacity); ?></td>
    <td><?php echo e($room->type); ?></td>
    <td><?php echo e($room->practical_type); ?></td>
    <td><?php echo e($room->status); ?></td>
    <td>
    <!-- Edit button -->
    <a href="<?php echo e(route('room.edit', $room->id)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bi bi-pencil-square"></i>
    </a>

    <!-- Delete button with confirmation -->
    <form action="<?php echo e(route('room.destroy', $room->id)); ?>" method="POST" style="display:inline;" 
          onsubmit="return confirm('Are you sure you want to delete this room?');">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button class="btn btn-sm btn-outline-danger" title="Delete">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td>

</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="7" class="text-center">No Record Found</td>
</tr>
<?php endif; ?>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/partials/room_table.blade.php ENDPATH**/ ?>