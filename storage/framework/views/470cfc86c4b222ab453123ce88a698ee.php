<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Timetables</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
        }

        h3 {
            text-align: center;
            color: green;
            margin-bottom: 5px;
        }

        .semester-text {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
        }

        .day-col {
            writing-mode: vertical-lr;
            text-align: center;
            font-weight: bold;
        }

        .badge {
            background: #17a2b8;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 4px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

<?php $__currentLoopData = $timetableData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <!-- HEADER -->
    <h3>
        TIME TABLE FOR <?php echo e(strtoupper($timetable['course1'])); ?> - <?php echo e($timetable['nta_level']); ?>


        <?php if(!empty($timetable['group_name'])): ?>
            (<?php echo e($timetable['group_name']); ?>)
        <?php endif; ?>
    </h3>

    <div class="semester-text">
        SEMESTER: <?php echo e(strtoupper($timetable['semester'])); ?>


        <?php if(!empty($timetable['semester_year'])): ?>
            - <?php echo e(strtoupper($timetable['semester_year'])); ?>

        <?php endif; ?>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Start - End</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Room</th>
            </tr>
        </thead>

        <tbody>

        <?php $__currentLoopData = $timetable['entries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayName => $entriesByDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
                $totalRowsForDay = 0;

                foreach($entriesByDay as $entry){
                    $start = \Carbon\Carbon::parse($entry->start_time);
                    $end = \Carbon\Carbon::parse($entry->end_time);
                    $creditHour = $entry->credit_hour ?? 3;
                    $duration = $start->diffInHours($end);

                    $totalRowsForDay += min($duration, $creditHour);
                }

                $dayPrinted = false;
            ?>

            <?php $__currentLoopData = $entriesByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
                    $start = \Carbon\Carbon::parse($entry->start_time);
                    $end = \Carbon\Carbon::parse($entry->end_time);
                    $creditHour = $entry->credit_hour ?? 3;
                    $countHour = 0;
                ?>

                <?php while($start < $end && $countHour < $creditHour): ?>

                <tr>

                    <!-- DAY -->
                    <?php if(!$dayPrinted): ?>
                        <td rowspan="<?php echo e($totalRowsForDay); ?>" class="day-col">
                            <?php echo e($dayName); ?>

                        </td>
                        <?php $dayPrinted = true; ?>
                    <?php endif; ?>

                    <!-- TIME -->
                    <td>
                        <?php echo e($start->format('H:i')); ?> -
                        <?php echo e($start->copy()->addHour()->format('H:i')); ?>

                    </td>

                    <!-- SUBJECT -->
                    <td>
                        <?php echo e($entry->subjectName); ?>


                        <?php if(!empty($entry->subject_group_name)): ?>
                            <?php
                                $courses = $groupCourses[$entry->subject_group_name] ?? [];
                            ?>

                            <?php if(count($courses)): ?>
                                <br>
                                <span class="badge">
                                    Joined: <?php echo e(implode(' + ', $courses)); ?>

                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>

                    <!-- TEACHER -->
                    <td>
                        <?php echo e($entry->firstname); ?>

                        <?php echo e($entry->middlename); ?>

                        <?php echo e($entry->lastname); ?>

                    </td>

                    <!-- ROOM -->
                    <td><?php echo e($entry->room_name); ?></td>

                </tr>

                <?php
                    $start->addHour();
                    $countHour++;
                ?>

                <?php endwhile; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>
    </table>

    <div class="page-break"></div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/pdfall.blade.php ENDPATH**/ ?>