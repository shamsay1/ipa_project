<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f6f9; padding:20px;">

    <div style="max-width:600px; margin:auto; background:white; padding:20px; border-radius:8px;">

        <h2 style="color:#dc3545;">Timetable Update</h2>

        <p>Dear User,</p>

        <p>
            This is to inform you that the timetable for the following subject has been <strong>changed</strong>:
        </p>

        <p>
            <strong>Subject:</strong> <?php echo e($subjectName); ?> <?php echo e($subjectCode); ?>

        </p>

        <hr>

        <p>
            <?php echo e($messageBody); ?>

        </p>

        <br>

        <p>
            Please check the updated timetable as soon as possible.
        </p>

        <br>

        <p>Regards,<br>Timetable System</p>

    </div>

</body>
</html>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/emailalert.blade.php ENDPATH**/ ?>