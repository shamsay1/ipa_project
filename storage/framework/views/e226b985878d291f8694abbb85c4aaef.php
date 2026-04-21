

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
        font-size: 13px;
        font-family: 'Times New Roman', Times, serif;
        text-align: center;
    }
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
    }
    .progress {
        height: 8px;
    }
    .btn-export {
        background-color: #0d6efd;
        color: white;
    }
    .btn-export:hover {
        background-color: #0b5ed7;
        color: white;
    }
    .action-btn {
        color: #6c757d;
        padding: 0.25rem 0.5rem;
        font-size: 1.2rem;
    }
    .action-btn:hover {
        color: #0d6efd;
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
    /* CSS for vertical text in day cells */
    .days strong {
        display: inline-block;
        white-space: nowrap;
    }
    /* Optional: Fade-in animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Styles for submenu */
    .submenu {
        padding-left: 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    .submenu.show {
        max-height: 500px;
    }
    .submenu .nav-link {
        padding: 0.5rem 0.8rem 0.5rem 2rem;
        font-size: 0.85rem;
    }
    .has-submenu::after {
        content: '\f282';
        font-family: 'Bootstrap Icons';
        float: right;
        transition: transform 0.3s;
    }
    .has-submenu.collapsed::after {
        transform: rotate(-90deg);
    }
    
    /* Print-specific styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .print-header {
            display: block !important;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .no-print {
            display: none !important;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            background-color: white !important;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 8px;
            background-color: white !important;
            color: black !important;
        }
        .table thead th {
            background-color: white !important;
            color: black !important;
            -webkit-print-color-adjust: exact;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: white !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: white !important;
        }
        .table-success {
            background-color: white !important;
        }
        /* Remove all colors and shadows */
        .table-container {
            background-color: white !important;
            box-shadow: none !important;
        }
        /* Ensure all text is black */
        * {
            color: black !important;
        }
    }
</style>

<?php $__env->startSection("content"); ?>
<div id="content">
    <div class="table-container p-3">
        <!-- Modal for Adding Teacher -->

        <div class="alert alert-dismissible fade show flash-message" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">My Timetable</h6>
                    <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <div class="table-responsive">
            <div id="mytable">

<div align="center" class="mb-3">
<img src="<?php echo e(asset('images/ipalogo1.png')); ?>" width="100px;">
</div>

<h4 style="font-family: 'Times New Roman';text-align:center;">
INSTITUTES OF PUBLIC AND ADMINISTRATION
</h4>

<h4 style="color:green;text-align:center">
Timetable for Teacher <?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?>

</h4>

<table class="table table-hover table-bordered table-sm">

<thead>

<tr style="background-color:#0f2a44;color:white">
<th colspan="100" style="text-align:center">
MY TIMETABLE
</th>
</tr>

<tr>
<th>DAY / TIME</th>

<?php $__currentLoopData = $timetable['timeslots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<th style="font-size:11px;line-height:1.2">

<div style="font-weight:bold;font-size: 20px;text-align: center">
<?php echo e($index + 1); ?>

</div>

<div style="font-size: 7px;text-align: center">
<?php echo e(date('h:ia', strtotime($slot['start']))); ?>

-
<?php echo e(date('h:ia', strtotime($slot['end']))); ?>

</div>

</th>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tr>

</thead>

<tbody>

<?php $__currentLoopData = $timetable['entries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $dayEntries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td style="font-weight:bold">
        <?php echo e(strtoupper($day)); ?>

    </td>

    <?php $__currentLoopData = $timetable['timeslots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td>

        <?php
            // tafuta entry kwa timeslot hii
            $found = $dayEntries
                ->where('start_time', $slot['start'])
                ->where('end_time', $slot['end'])
                ->first();
        ?>

        <?php if($found): ?>

            <?php if($found->group_name): ?>

                <strong>
                    <?php echo e(strtoupper($found->group_name)); ?>

                </strong>
                <br>

                
                <?php
                    $groupSubjects = collect();
                    if(isset($groupCourses[$found->group_name])) {
                        $groupSubjects = $groupCourses[$found->group_name];
                    }
                ?>

                <?php echo e(implode(' + ', $groupSubjects->toArray())); ?>

                <br>

                ROOM: <?php echo e($found->room_name); ?>


            <?php else: ?>

                <strong>
                    <?php echo e($found->subjectName); ?> (<?php echo e($found->subjectCode); ?>)
                </strong>
                <br>

                <?php echo e($found->fullCourseName); ?> (<?php echo e($found->nta_level); ?>)
                <br>

                ROOM: <?php echo e($found->room_name); ?>


            <?php endif; ?>

        <?php endif; ?>

    </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody>



</table>

</div>

           <div class="text-center mt-4 no-print">

                <button 
                class="btn btn-primary"
                onclick="printTeacherTimetable(this)"

                data-timetable='<?php echo json_encode($timetable["entries"], 15, 512) ?>'
                data-timeslots='<?php echo json_encode($timetable["timeslots"], 15, 512) ?>'
                data-teacher="<?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?>"
                >

                <i class="fas fa-print"></i> Print Timetable

                </button>

                </div>
        </div>
    </div>
</div>


 <script>
function printTeacherTimetable(button) {
    const timetableData = JSON.parse(button.getAttribute("data-timetable"));
    const timeslots = JSON.parse(button.getAttribute("data-timeslots"));
    const teacherName = button.getAttribute("data-teacher");

    const days = Object.keys(timetableData);

    // ==== Collect GROUP COURSES (use fullCourseName) ====
    const groupCourses = {};

    days.forEach(day => {
        timetableData[day].forEach(entry => {
            if(entry.group_name){
                if(!groupCourses[entry.group_name]) groupCourses[entry.group_name] = [];

                // tumia fullCourseName (ina Roman tayari)
                if(!groupCourses[entry.group_name].includes(entry.fullCourseName)){
                    groupCourses[entry.group_name].push(entry.fullCourseName);
                }
            }
        });
    });

    // ===== open print window =====
    const printWindow = window.open('', '', 'width=1200,height=900');

    printWindow.document.write(`
    <html>
    <head>
        <title>Teacher ${teacherName} Timetable</title>
        <style>
            @page { margin:0 }
            body{ margin:40px; font-family:'Times New Roman'; }
            h2,h4{text-align:center; margin:3px;}
            table{width:100%; border-collapse:collapse; margin-top:20px; font-size:13px;}
            th,td{border:1px solid black; padding:6px; text-align:center; vertical-align:middle;}
            th{background:#e9ecef; font-weight:bold;}
            td:first-child{font-weight:bold; background:#f2f2f2;}
        </style>
    </head>
    <body onload="window.print();window.close();">
        <h2>THE INSTITUTE OF PUBLIC AND ADMINISTRATION</h2>
        <h4>TIMETABLE FOR TEACHER: ${teacherName.toUpperCase()}</h4>

        <table>
            <thead>
                <tr>
                    <th>DAY / TIME</th>
                    ${timeslots.map((slot,index) => `
                        <th style="font-size:11px;line-height:1.2">
                            <div style="font-weight:bold;font-size:25px">${index + 1}</div>
                            <div>${slot.start.slice(0,5)} - ${slot.end.slice(0,5)}</div>
                        </th>
                    `).join('')}
                </tr>
            </thead>

            <tbody>
                ${days.map(day => `
                    <tr>
                        <td>${day.toUpperCase()}</td>

                        ${timeslots.map(slot => {

                            const entry = timetableData[day].find(e =>
                                e.start_time === slot.start && e.end_time === slot.end
                            );

                            if(entry){

                                let html = '';

                                if(entry.group_name){

                                    const groupSubjects = groupCourses[entry.group_name] || [];

                                    // 🔥 SHOW GROUP NAME + COURSES
                                    html += '<strong>' + entry.group_name.toUpperCase() + '</strong><br>';
                                    html += groupSubjects.join(' + ') + '<br>';
                                    html += 'ROOM: ' + entry.room_name;

                                }else{

                                    html += `<strong>${entry.subjectName} (${entry.subjectCode})</strong><br>`;
                                    html += `${entry.fullCourseName} (${entry.nta_level})<br>`;
                                    html += 'ROOM: ' + entry.room_name;
                                }

                                return `<td>${html}</td>`;

                            }else{
                                return `<td></td>`;
                            }

                        }).join('')}

                    </tr>
                `).join('')}
            </tbody>
        </table>

    </body>
    </html>
    `);

    printWindow.document.close();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/teachertbl1.blade.php ENDPATH**/ ?>