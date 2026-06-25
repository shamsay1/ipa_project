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
        <li><a href="<?php echo e(url('/attendance')); ?>">Emergency less</a></li>


        
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


 <section class="hero">
    <div style="margin-top: -60px;">
        <img src="<?php echo e(asset('images/ipalogo1.png')); ?>" width="100">
    </div>
        <h2>INSTITUTE OF PUBLIC AND<br> ADMINISTRATION </h2>
        <?php if(Auth::user()->gender =="Male"): ?>
        <h2>Welcome Mr <?php echo e(Auth::user()->firstname); ?></h2>
        <?php else: ?>
        <h2>Welcome Madam <?php echo e(Auth::user()->firstname); ?></h2>
        <?php endif; ?>
        <p>
            Mfumo wa kitaalamu wa kusimamia ratiba za masomo kwa vyuo na taasisi za elimu.
        </p>
    </section>

    <!-- CONTENT -->
    <section class="container">
        <div class="cards">
            <div class="card">
                <p>Total subjects</p>
                <h1><?php echo e($subjectCount); ?></h1>
            </div>

            <div class="card">
                <p>Total periods</p>
                <h1><?php echo e($periodCount); ?></h1>
            </div>

            <div class="card">
                <div class="container">
    <h3 class="mb-4" style="font-family: 'Times New Roman', Times, serif; text-align: center; color: green;">
        Bar Graph: Periods per day
    </h3>
    <canvas id="periodsChart" width="400" height="300"></canvas>
</div>

<!-- ✅ Chart.js (latest stable - v4.4.5) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.5/dist/chart.umd.min.js"></script>

<script>
    const ctx = document.getElementById('periodsChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels, 15, 512) ?>,
            datasets: [{
                label: 'Number of periods',
                data: <?php echo json_encode($periods, 15, 512) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
            </div>
            
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 ShamisTech. All Rights Reserved.</p>
    </footer>
    
    </div>

</body>
</html>
<?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/teacherDash.blade.php ENDPATH**/ ?>