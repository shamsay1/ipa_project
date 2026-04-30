            
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
                background-color: #d1e7dd; /* Light green background */
                border-color: #badbcc; /* Darker green border */
                color: #0f5132; /* Dark green text */
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
                color: #28a745; /* Deeper green for the icon */
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
                    .days{
                        writing-mode: vertical-rl;
                        font-weight: bold;
                        font-size: 20px;
                        text-align: center;
                    }
            </style>
            <?php $__env->startSection("content"); ?>
            <div id="content">
                    <div class="table-container p-3">
                        <div class="row mb-4">
                <div class="row align-items-center mb-4">
                
            <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                <?php if($systemTimetable->status != "created"): ?>
                <form id="generateForm" action="<?php echo e(route('generate.timetable')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary" onclick="showLoading()">
                        <i class="fas fa-cog"></i> Generate Timetable
                    </button>
                </form>
                <?php endif; ?>

                

            </div>

            
                        

                    </button>
                    


                
            </div>
            <form action="<?php echo e(route('timetable.generate')); ?>" method="GET">
            <div class="row">
                <div class="col-md-3">
            <select name="course" class="form-select">
                <option value="">-- Select Course --</option>

                <!-- Diploma Courses -->
                <optgroup label="Diploma Courses">
                <?php $__currentLoopData = $courses->where('course_level', 'Diploma'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($course->id); ?>" <?php echo e(request('course') == $course->id ? 'selected' : ''); ?>>
                    <?php echo e($course->courseName); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </optgroup>

                <!-- Degree Courses -->
                <optgroup label="Degree Courses">
                <?php $__currentLoopData = $courses->where('course_level', 'Degree'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($course->id); ?>" <?php echo e(request('course') == $course->id ? 'selected' : ''); ?>>
                    <?php echo e($course->courseName); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </optgroup>

            </select>
            </div>

                <div class="col-md-3">
                <select name="nta" class="form-select">
                    <option value="">-- Select NTA Level --</option>
                    <?php $__currentLoopData = $ntaLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($nta); ?>" <?php echo e(request('nta') == $nta ? 'selected' : ''); ?>>
                        <?php echo e($nta); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                </div>

                <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
            </form>



                        <div class="alert alert-dismissible fade show flash-message" role="alert">
            <div class="d-flex align-items-center">
                
                <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
                <h6 class="alert-heading mb-1">Generate Timetable</h6>
                <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            </div>

            <div id="loading" style="display: none; margin-top: 20px;">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="ms-3">
                                            <h5 style="color: green;font-style: italic">Generating timetable, please wait...</h5>
                                            <p>This process may take several minutes. Do not refresh or close this page.</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="result" style="display: none; margin-top: 20px;"></div>

                        <?php $__currentLoopData = $timetableData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="mb-5">

            <h3 class="text-center mb-4" style="color: green;font-family: 'Times New Roman', Times, serif">

            TIME TABLE FOR <?php echo e(strtoupper($timetable['course1'])); ?> - <?php echo e($timetable['nta_level']); ?>


            <?php if(!empty($timetable['group_name'])): ?>
            (<?php echo e($timetable['group_name']); ?>)
            <?php endif; ?>

            <br>

            <span style="font-size:16px;">
            SEMESTER: <?php echo e(strtoupper($timetable['semester'])); ?>


            <?php if(!empty($timetable['semester_year'])): ?>
            - <?php echo e(strtoupper($timetable['semester_year'])); ?>

            <?php endif; ?>

            </span>

            </h3>


            <div class="timetable-container">

            <table class="table table-bordered">

            <thead>
            <tr>
            <th>Day</th>
            <th>Start - End</th>
            <th>Subject</th>
            <th>Teacher</th>
            <th>Room</th>
            <th>Action</th>
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

            $totalRowsForDay += min($duration,$creditHour);
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

            
            <?php if(!$dayPrinted): ?>

            <td rowspan="<?php echo e($totalRowsForDay); ?>"
            style="writing-mode: vertical-lr;text-align:center;font-weight:bold;">

            <?php echo e($dayName); ?>


            </td>

            <?php $dayPrinted = true; ?>

            <?php endif; ?>


            
            <td>

            <?php echo e($start->format('H:i')); ?>


            -

            <?php echo e($start->copy()->addHour()->format('H:i')); ?>


            </td>


            
            <td>
                <?php echo e($entry->subjectName); ?>


                <?php if(!empty($entry->subject_group_name)): ?>
                    <?php
                        $courses = $groupCourses[$entry->subject_group_name] ?? [];
                    ?>

                    <span class="badge bg-info text-dark">
                        Joined: <?php echo e(implode(' + ', $courses)); ?>

                    </span>
                <?php endif; ?>
            </td>
            
            <td><?php echo e($entry->firstname); ?> <?php echo e($entry->middlename); ?> <?php echo e($entry->lastname); ?></td>


            
            <td><?php echo e($entry->room_name); ?></td>


            
            <td>

<a href="<?php echo e(route('timetable.edit',$entry->timetable_id)); ?>"
class="btn btn-sm btn-outline-primary">
Edit
</a>
<?php if($systemTimetable->status == "created"): ?>
<button 
class="btn btn-sm btn-success mt-1"
onclick="openEmailModal(this)"

data-subject="<?php echo e($entry->subjectName); ?>"
data-subject-code="<?php echo e($entry->subjectCode); ?>"
data-teacher="<?php echo e($entry->firstname); ?> <?php echo e($entry->middlename); ?> <?php echo e($entry->lastname); ?>"
data-teacher-email="<?php echo e($entry->email ?? ''); ?>"

data-cr="<?php echo e($entry->cr_name); ?> <?php echo e($entry->cr_name2); ?> <?php echo e($entry->cr_name3); ?>"
data-cr-email="<?php echo e($entry->cr_email ?? ''); ?>"

data-course="<?php echo e($timetable['course1']); ?>"
data-nta="<?php echo e($entry->nta_level); ?>"
data-semester="<?php echo e($entry->semester_name); ?>"
>

Email
</button>
<?php endif; ?>

<div class="modal fade" id="emailModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Send Email</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="<?php echo e(route('send.email')); ?>" method="POST">
<?php echo csrf_field(); ?>

<div class="modal-body">

<p><strong>Subject:</strong> <span id="modalSubject"></span></p>
<p><strong>Code:</strong> <span id="modalSubjectCode"></span></p>
<p><strong>Teacher:</strong> <span id="modalTeacher"></span></p>
<p><strong>CR:</strong> <span id="modalCR"></span></p>


<input type="hidden" name="subject" id="form_subject">
<input type="hidden" name="subject_code" id="form_subject_code">
<input type="hidden" name="teacher_email" id="form_teacher_email">
<input type="hidden" name="cr_email" id="form_cr_email">

<div class="mb-2">

</div>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-primary">
Send Email
</button>
</div>

</form>

</div>
</div>
</div>



</td>


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


            <!-- PRINT BUTTON -->

            <div class="text-center mt-3 nodis">

            <button
            class="btn btn-success btn-sm"
            onclick="printTimetable(this)"

            data-timetable='<?php echo json_encode($timetable["entries"], 15, 512) ?>'
            data-course="<?php echo e($timetable['course']); ?>"
            data-nta="<?php echo e($timetable['nta_level']); ?>"
            data-group="<?php echo e($timetable['group_name'] ?? ''); ?>"
            data-semester="<?php echo e($timetable['semester']); ?>"
            data-year="<?php echo e($timetable['semester_year'] ?? ''); ?>"
            >

            Print Timetable

            </button>

            </div>


            </div>

            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        

                        
                    </div>
                </div>
                <script>
function openEmailModal(btn){

    // SET TEXT
    document.getElementById('modalSubject').innerText = btn.dataset.subject;
    document.getElementById('form_subject_code').value = btn.dataset.subjectCode;
    document.getElementById('modalTeacher').innerText = btn.dataset.teacher;
    document.getElementById('modalCR').innerText = btn.dataset.cr || 'N/A';

    // SET FORM VALUES
    document.getElementById('form_subject').value = btn.dataset.subject;
    document.getElementById('form_teacher_email').value = btn.dataset.teacherEmail;
    document.getElementById('form_cr_email').value = btn.dataset.crEmail;

    // OPEN MODAL
    let modal = new bootstrap.Modal(document.getElementById('emailModal'));
    modal.show();
}
</script>


            <script>
            function showLoading() {
                document.getElementById('loading').style.display = 'block';
                document.querySelector('button[type="submit"]').disabled = true;
                
                // Submit form via AJAX
                event.preventDefault();
                
                const form = document.getElementById('generateForm');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    
                    if (data.success) {
                        document.getElementById('result').innerHTML = `
                            <div class="alert alert-success">
                                <h5>Timetable Generated Successfully!</h5>
                                <p>${data.message}</p>
                            </div>
                        `;
                    } else {
                        document.getElementById('result').innerHTML = `
                            <div class="alert alert-success">
                                <h5>Timetable Generated Successfully!</h5>
                                <p>${data.message}</p>
                            </div>
                        `;
                        // document.getElementById('result').innerHTML = `
                        //     <div class="alert alert-danger">
                        //         <h5>Error Generating Timetable</h5>
                        //         <p>${data.message}</p>
                        //         <button class="btn btn-warning" onclick="window.location.reload()">Try Again</button>
                        //     </div>
                        // `;
                    }
                    document.getElementById('result').style.display = 'block';
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('result').innerHTML = `
                        <div class="alert alert-danger">
                            <h5>Error Generating Timetable</h5>
                            <p>${error.message}</p>
                            <button class="btn btn-warning" onclick="window.location.reload()">Try Again</button>
                        </div>
                    `;
                    document.getElementById('result').style.display = 'block';
                });
            }
            </script>
            <script>
            function printTimetable(button) {

                const timetableData = JSON.parse(button.getAttribute("data-timetable"));

                const courseName = button.getAttribute("data-course") || "";
                const ntaLevel = button.getAttribute("data-nta") || "";
                const groupName = button.getAttribute("data-group") || "";
                const semester = button.getAttribute("data-semester") || "";
                const active = button.getAttribute("data-active") || "";

                // ===== Get & sort unique timeslots =====
                let timeslots = [];

                for (let day in timetableData) {
                    timetableData[day].forEach(e => {

                        const slot = `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}`;

                        if (!timeslots.includes(slot)) {
                            timeslots.push(slot);
                        }

                    });
                }

                timeslots.sort((a, b) => {
                    const startA = a.split(' - ')[0];
                    const startB = b.split(' - ')[0];
                    return startA.localeCompare(startB);
                });

                const days = Object.keys(timetableData);

                // ===== Open Print Window =====
                const printWindow = window.open('', '', 'width=1200,height=900');

                printWindow.document.write(`
                    <html>
                    <head>
                        <title>${courseName} - ${ntaLevel} Timetable</title>

                        <style>

                            @page { margin:0 }

                            body{
                                margin:40px;
                                background:white;
                                font-family:'Times New Roman', Times, serif;
                                color:black;
                            }

                            h2,h4,h5{
                                text-align:center;
                                margin:2px 0;
                                line-height:1.4;
                            }

                            table{
                                width:100%;
                                border-collapse:collapse;
                                font-size:13px;
                                margin-top:25px;
                            }

                            th,td{
                                border:1px solid #000;
                                padding:6px;
                                text-align:center;
                                vertical-align:middle;
                            }

                            th{
                                background:#e9ecef;
                                text-transform:uppercase;
                                font-weight:bold;
                            }

                            td:first-child{
                                font-weight:bold;
                                background:#f2f2f2;
                                text-transform:uppercase;
                            }

                            tr:nth-child(even){
                                background:#f8f9fa;
                            }

                        </style>

                    </head>

                    <body onload="window.print();window.close();">

                        <h2>THE INSTITUTE OF PUBLIC AND ADMINISTRATION</h2>

                        <h4>
                            ${courseName.toUpperCase()} - ${ntaLevel.toUpperCase()}
                        </h4>

                        ${groupName ? `<h5>GROUP: ${groupName.toUpperCase()}</h5>` : ""}

                        <h5>
                            TIMETABLE FOR ${semester.toUpperCase()}
                            ${active ? ': ' + active.toUpperCase() : ""}
                        </h5>

                        <table>

                            <thead>

                                <tr>
                                    <th>DAY / TIME</th>
                                    ${timeslots.map(slot => `<th>${slot}</th>`).join('')}
                                </tr>

                            </thead>

                            <tbody>

                                ${days.map(day => `

                                    <tr>

                                        <td>${day.toUpperCase()}</td>

                                        ${timeslots.map(slot => {

                                            const entry = timetableData[day].find(e =>
                                                `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}` === slot
                                            );

                                            if(entry){

                                                return `
                                                    <td>

                                                        <strong>${entry.subjectName}</strong><br>

                                                        ${entry.firstname} ${entry.middlename} ${entry.lastname}<br>

                                                        ${entry.room_name}

                                                    </td>
                                                `;

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

            <!-- CSS -->
            <style>
            .nodis { display: block; }
            @media print {
                .nodis { display: none !important; }
            }
            </style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/timetable/generate.blade.php ENDPATH**/ ?>