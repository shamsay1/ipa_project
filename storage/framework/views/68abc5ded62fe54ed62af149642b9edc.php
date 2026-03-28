<!DOCTYPE html>
<html>
<head>
    <title>API Users</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>

<h2>Users from API</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($user['id']); ?></td>
            <td><?php echo e($user['name']); ?></td>
            <td><?php echo e($user['email']); ?></td>
            <td><?php echo e($user['username']); ?></td>
            <td>
                <a href="<?php echo e(route('users.show', $user['id'])); ?>">
                    View Details
                </a>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/api.blade.php ENDPATH**/ ?>