<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: no-box;
            font-family: Arial, Helvetica, sans-serif;
        }
 .page-wrapper {
        height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .btn1{
        background-color: rgb(41, 190, 103);
        padding: 7px;
        margin: 10px 10px 10px 10px;
        color: white;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        
         
    }

    .page-content {
        flex: 1;
    }

    /* TABLE SCROLL FIX */
    .table-scroll {
        overflow-x: auto;
        background: #ffffff;
        border-radius: 6px;
    }

    .table-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        vertical-align: middle;
        font-size: 14px;
        font-size: 10px;
    }
        body {
            background: #f8fafc; /* academic light */
            color: #1e293b;
        }

        /* HEADER */
        header {
            background: #0f2a44; /* navy blue */
            color: white;
            padding: 15px 20px;
            border-bottom: 4px solid #c9a227; /* gold line */
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav h1 {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            gap: 18px;
        }

        .nav ul li a {
            color: #f8fafc;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
        }

        .nav ul li a:hover {
            color: #c9a227; /* gold hover */
        }

        /* HERO */
        .hero {
            background: linear-gradient(to right, #0f2a44, #143a5e);
            color: white;
            text-align: center;
            padding: 90px 20px;
        }

        .hero h2 {
            font-size: 34px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 16px;
            max-width: 650px;
            margin: auto;
            color: #e5e7eb;
        }

        /* CONTENT */
        .container {
            padding: 50px 20px;
            max-width: 1100px;
            margin: auto;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #c9a227; /* academic accent */
        }

        .card h3 {
            margin-bottom: 12px;
            color: #0f2a44;
        }

        .card p {
            color: #475569;
        }

        /* FOOTER */
        footer {
            background: #0f2a44;
            color: #e5e7eb;
            text-align: center;
            padding: 18px;
            margin-top: 50px;
        }

        /* RESPONSIVE NAV */
        @media (max-width: 600px) {
            .nav ul {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .hero h2 {
                font-size: 26px;
            }
        }
        .chart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
        }
    </style>
</head>
<body>
   <div class="content">
    <!-- HEADER -->
    <header>
    <div class="nav">
        <h1>Timetable Management System</h1>
      <ul>
        <li><a href="<?php echo e(url('/teacher-dash')); ?>">Home</a></li>
        <li><a href="<?php echo e(url('/teachersub')); ?>">Subjects</a></li>
        <li><a href="<?php echo e(url('/teachettbl')); ?>">Timetable</a></li>
        <li><a href="<?php echo e(url('/profile')); ?>">Profile</a></li>

        
        <li class="ms-auto">
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="mb-0">
                <?php echo csrf_field(); ?>
                 <button type="submit" style="padding: 5px;background-color: #c9a227;border-radius: 5px;color: white;border: none;cursor: pointer">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </li>

</ul>


    </div>
</header>
  <div class="page-content">

        <section class="container mt-5">
            
          

            <!-- TABLE SCROLLABLE WRAPPER -->
            <div class="table-scroll shadow">
                <div style="text-align: center;margin-top: 10px;">
                <img src="<?php echo e(asset('images/ipalogo1.png')); ?>" alt="" width="120">
            </div>
     <h3 style="text-align: center;margin-top: 10px">INSTITUTE OF PUBLIC AND ADMINISTRATION</h3>
                <h5 style="text-align: center">TEACHER SUBJECT: <b><?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?></b></h5><br>
              <div class="table-responsive">
                <div class="text-end mt-3 nodis">
    <button 
                class="btn1"
                onclick="printTeacherTimetable(this)"

                data-timetable='<?php echo json_encode($timetable["entries"], 15, 512) ?>'
                data-timeslots='<?php echo json_encode($timetable["timeslots"], 15, 512) ?>'
                data-teacher="<?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->middlename); ?> <?php echo e(Auth::user()->lastname); ?>"
                >

                <i class="fas fa-print"></i> Print Timetable

                </button>
</div>
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

            </div>

        </section>

    </div>

    <!-- FOOTER (ALWAYS BOTTOM) -->
    <footer class="bg-dark text-light py-3">
        <p class="text-center mb-0">&copy; 2025 ShamisTech. All Rights Reserved.</p>
    </footer>


 
    
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
</body>
</html>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/teachertbl.blade.php ENDPATH**/ ?>