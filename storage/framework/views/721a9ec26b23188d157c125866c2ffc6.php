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
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background: #f8fafc; /* academic light */
            color: #1e293b;
        }
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
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
            align-items: center;
        }
        .nav ul li a {
            color: #f8fafc;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
        }
        .nav ul li a:hover {
            color: #c9a227;
        }
        .container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* CARDS STYLING - ZINAPANGANA 3 KWA ROW */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); 
            gap: 25px;
            margin-top: 20px;
        }
        .subject-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            border-top: 4px solid #c9a227;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        .subject-card h3 {
            color: #0f2a44;
            margin-bottom: 12px;
            font-size: 18px;
            line-height: 1.4;
        }
        .subject-card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 6px;
        }

        /* TABLE STYLING - IWE NA MUONEKANO MZURI SANA */
        .table-scroll {
            overflow-x: auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 15px;
        }
        th, td {
            padding: 14px 18px;
            text-align: left; /* Alignment ya kushoto inaleta weledi zaidi */
            font-size: 14px;
        }
        th {
            background-color: #0f2a44;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }
        /* Curve kona za table header */
        th:first-child { border-top-left-radius: 8px; }
        th:last-child { border-top-right-radius: 8px; }
        
        td {
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background-color: #f8fafc; /* Inabadili rangi mshale ukikaa juu ya mstari */
        }

        /* BADGES FOR STATUS */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }
        .badge-present {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-absent {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* BACK BUTTON */
        .btn-back {
            background-color: #475569;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn-back:hover {
            background-color: #1e293b;
        }

        footer {
            background: #0f2a44;
            color: #e5e7eb;
            text-align: center;
            padding: 18px;
            margin-top: auto;
        }

        /* UTILITIES */
        .d-none { display: none !important; }
        .text-center { text-align: center; }

        /* RESPONSIVE DESIGN - KWA AJILI YA SIMU NA TABLET */
        @media (max-width: 992px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr); /* Zinakuwa mbili kwenye tablet */
            }
        }
        @media (max-width: 640px) {
            .cards-grid {
                grid-template-columns: 1fr; /* Inakuwa moja kwenye simu ndogo */
            }
            .container {
                padding: 20px 15px;
            }
            .nav h1 {
                font-size: 18px;
                margin-bottom: 10px;
            }
            .nav {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <!-- HEADER -->
    <header>
        <div class="nav">
            <h1>Timetable Management System</h1>
            <ul>
                <li><a href="<?php echo e(url('/teacher-dash')); ?>">Home</a></li>
                <li><a href="<?php echo e(url('/teachersub')); ?>" style="color: #c9a227;">Subjects</a></li>
                <li><a href="<?php echo e(url('/teachettbl')); ?>">Timetable</a></li>
                <li><a href="<?php echo e(url('/profile')); ?>">Profile</a></li>
                <li><a href="<?php echo e(url('/attendance')); ?>">Emergency less</a></li>
                <li><a href="<?php echo e(url('/attendance1')); ?>">View attendance</a></li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" style="padding: 6px 12px; background-color: #c9a227; border-radius: 5px; color: white; border: none; cursor: pointer; font-weight: bold;">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <div class="page-content">
    <section class="container mt-5">
        <div class="table-scroll shadow" style="border-radius: 5px">
           <div class="text-center" style="margin-bottom: 35px;">
            <img src="<?php echo e(asset('images/ipalogo1.png')); ?>" alt="IPA Logo" width="100">
            <h3 style="margin-top: 15px; color: #0f2a44; letter-spacing: 0.5px;">INSTITUTE OF PUBLIC AND ADMINISTRATION</h3>
            <h5 style="color: #64748b; margin-top: 5px; font-weight: 500;">MWALIMU: <b style="color: #0f2a44;"><?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->middlename); ?> <?php echo e(Auth::user()->lastname); ?></b></h5>
        </div>

        <!-- SEHEMU YA MASOMO (SUBJECTS SECTION) -->
        <div id="subjects-section">
            <h4 style="border-left: 4px solid #0f2a44; padding-left: 10px; margin-bottom: 20px; color: #0f2a44; font-size: 16px;">MASOMO YAKO TAYARI</h4>
            <div class="cards-grid">
                <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="subject-card" onclick="showAttendance('<?php echo e($subject->id); ?>', '<?php echo e($subject->subjectName); ?> (<?php echo e($subject->subjectCode); ?>)')">
                        <div>
                            <h3><?php echo e($subject->subjectName); ?></h3>
                            <p><b>Code:</b> <?php echo e($subject->subjectCode); ?></p>
                            <p><b>Level:</b> <?php echo e($subject->nta_level); ?></p>
                        </div>
                        <p style="margin-top: 15px; color: #c9a227; font-weight: bold; font-size: 13px; border-top: 1px dashed #f1f5f9; padding-top: 10px;">
                            Angalia Mahudhurio &rarr;
                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 40px; background: white; border-radius: 8px;">Huna somo lolote lililosajiliwa kwa sasa.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- SEHEMU YA ATTENDANCE (ATTENDANCE SECTION - HIDDEN BY DEFAULT) -->
        <div id="attendance-section" class="d-none">
            <button class="btn-back" onclick="hideAttendance()">&larr; Rudi kwenye Masomo</button>
            <h4 id="selected-subject-title" style="color: #0f2a44; margin-bottom: 20px; border-left: 4px solid #c9a227; padding-left: 10px;"></h4>
            
            <div class="table-scroll">
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- Kila somo lina jedwali lake lililofichwa lenye ID maalum -->
                    <table id="attendance-table-<?php echo e($subject->id); ?>" class="attendance-table d-none">
                        <thead>
                            <tr>
                                <th style="width: 80px;">#</th>
                                <th>Tarehe</th>
                                <th>Kipindi (Timeslot)</th>
                                <th>NTA Level</th>
                                <th style="text-align: center;">Status / Hali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $subject->attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($attendance->created_at)->format('d-m-Y')); ?></td>
                                    <td><?php echo e($attendance->timeslot ?? 'N/A'); ?></td>
                                    <td><?php echo e($subject->nta_level); ?></td>
                                    <td style="text-align: center;">
                                        <?php if($attendance->status2 == 'present' || $attendance->status == 'present'): ?>
                                            <span class="badge badge-present">Present</span>
                                        <?php else: ?>
                                            <span class="badge badge-absent">Absent</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">
                                        Hakuna kumbukumbu za mahudhurio kwenye somo hili bado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        </div>


    </section>
    </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 ShamisTech. All Rights Reserved.</p>
    </footer>
</div>

<!-- JAVASCRIPT YA KUBADILI CONTROLS -->
<script>
    function showAttendance(subjectId, subjectTitle) {
        // Ficha masomo yote
        document.getElementById('subjects-section').classList.add('d-none');
        
        // Onesha sehemu ya attendance
        document.getElementById('attendance-section').classList.remove('d-none');
        document.getElementById('selected-subject-title').innerText = "Kumbukumbu za " + subjectTitle;

        // Ficha majedwali yote ya attendance kwanza
        let tables = document.querySelectorAll('.attendance-table');
        tables.forEach(table => table.classList.add('d-none'));

        // Onesha jedwali la somo lililochaguliwa tu
        document.getElementById('attendance-table-' + subjectId).classList.remove('d-none');
    }

    function hideAttendance() {
        // Ficha sehemu ya attendance na urudishe masomo
        document.getElementById('attendance-section').classList.add('d-none');
        document.getElementById('subjects-section').classList.remove('d-none');
    }
</script>

</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/viewattend.blade.php ENDPATH**/ ?>