<table border="1" cellspacing="0" cellpadding="5">

    
    <tr>
        <th rowspan="3">Day</th>
        <th rowspan="3">Time</th>

        <?php
        $ntaLevels = $timetable->pluck('nta_level')
            ->unique()
            ->sortBy(fn($nta)=> (int) preg_replace('/\D/', '', $nta))
            ->values();
        ?>

        <?php $__currentLoopData = $ntaLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $courses = $timetable->where('nta_level', $nta)
                    ->pluck('courseName')
                    ->unique();
            ?>

            
            <?php
                $span = 0;
                foreach($courses as $course){
                    $span += $timetable
                        ->where('nta_level', $nta)
                        ->where('courseName', $course)
                        ->pluck('group_name')
                        ->unique()
                        ->count();
                }
            ?>

            <th colspan="<?php echo e($span); ?>"><?php echo e($nta); ?></th>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>

    
    <tr>
        <?php $__currentLoopData = $ntaLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $courses = $timetable->where('nta_level', $nta)
                    ->pluck('courseName')
                    ->unique();
            ?>

            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $groups = $timetable
                        ->where('nta_level', $nta)
                        ->where('courseName', $course)
                        ->pluck('group_name')
                        ->unique();
                ?>

                <th colspan="<?php echo e(max($groups->count(),1)); ?>"><?php echo e($course); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>

    
    <tr>
        <?php $__currentLoopData = $ntaLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $courses = $timetable->where('nta_level', $nta)
                    ->pluck('courseName')
                    ->unique();
            ?>

            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $groups = $timetable
                        ->where('nta_level', $nta)
                        ->where('courseName', $course)
                        ->pluck('group_name')
                        ->unique();
                ?>

                <?php if($groups->count() > 0): ?>
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($group); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <th>-</th>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>

    
    <?php
        $days = $timetable->pluck('day_name')->unique();
        $slots = $timetable->map(fn($item)=>[
            'start'=>$item->start_time,
            'end'  =>$item->end_time
        ])->unique()->sortBy('start')->values();
    ?>

    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <?php if($loop->first): ?>
                    <td rowspan="<?php echo e(count($slots)); ?>"><b><?php echo e(strtoupper($day)); ?></b></td>
                <?php endif; ?>

                <td><?php echo e($slot['start']); ?> - <?php echo e($slot['end']); ?></td>

                <?php $__currentLoopData = $ntaLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $courses = $timetable->where('nta_level', $nta)
                            ->pluck('courseName')
                            ->unique();
                    ?>

                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $groups = $timetable
                                ->where('nta_level', $nta)
                                ->where('courseName', $course)
                                ->pluck('group_name')
                                ->unique();
                        ?>

                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $cell = $timetable->first(function ($item) use ($day, $slot, $course, $nta, $group) {
                                    return $item->day_name == $day
                                        && $item->start_time == $slot['start']
                                        && $item->end_time == $slot['end']
                                        && $item->courseName == $course
                                        && $item->nta_level == $nta
                                        && $item->group_name == $group;
                                });
                            ?>

                            <td>
                                <?php if($cell): ?>
                                    <b><?php echo e($cell->subjectName); ?></b><br>
                                    Room: <?php echo e($cell->room); ?>

                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <?php if($groups->count() == 0): ?>
                            <td></td>
                        <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/exports/department_timetable.blade.php ENDPATH**/ ?>