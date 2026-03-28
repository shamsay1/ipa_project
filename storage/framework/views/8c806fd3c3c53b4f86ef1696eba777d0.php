<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
</head>
<body>

<a href="<?php echo e(route('users.index')); ?>">⬅ Back</a>

<h2><?php echo e($user['name']); ?></h2>

<p><strong>Email:</strong> <?php echo e($user['email']); ?></p>
<p><strong>Username:</strong> <?php echo e($user['username']); ?></p>
<p><strong>Phone:</strong> <?php echo e($user['phone']); ?></p>
<p><strong>Website:</strong> <?php echo e($user['website']); ?></p>

<hr>

<h3>Address</h3>
<p>
    <?php echo e($user['address']['street']); ?>,
    <?php echo e($user['address']['suite']); ?><br>
    <?php echo e($user['address']['city']); ?>,
    <?php echo e($user['address']['zipcode']); ?>

</p>

<h4>Geo</h4>
<p>
    Lat: <?php echo e($user['address']['geo']['lat']); ?> <br>
    Lng: <?php echo e($user['address']['geo']['lng']); ?>

</p>

<hr>

<h3>Company</h3>
<p>
    <strong>Name:</strong> <?php echo e($user['company']['name']); ?> <br>
    <strong>Catch Phrase:</strong> <?php echo e($user['company']['catchPhrase']); ?> <br>
    <strong>Business:</strong> <?php echo e($user['company']['bs']); ?>

</p>

</body>
</html>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/show.blade.php ENDPATH**/ ?>