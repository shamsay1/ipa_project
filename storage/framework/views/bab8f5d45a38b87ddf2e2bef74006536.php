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
        padding: 5px;
        margin: 10px 10px 10px 10px;
        color: white;
        border-radius: 4px;
        border: none;
        
         
    }
    .password-card {
    max-width: 500px;
    margin: 30px auto;
    background: #ffffff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: 0.3s ease;
}

/* Title */
.password-card h4 {
    font-weight: 600;
    color: #0f2a44;
    letter-spacing: 0.5px;
}

/* Labels */
.password-card .form-label {
    font-weight: 500;
    color: #495057;
}

/* Input Fields */
.password-card .form-control {
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 5px; 
    border: 1px solid #ced4da;
    transition: all 0.2s ease;
}

/* Focus Effect */
.password-card .form-control:focus {
    border-color: #0f2a44;
    box-shadow: 0 0 0 0.2rem rgba(15, 42, 68, 0.15);
}

/* Error State */
.password-card .is-invalid {
    border-color: #dc3545;
}

/* Button */
.password-card .btn-primary {
    background-color: #0f2a44;
    border: none;
    padding: 10px;
    color: white;
    font-weight: 600;
    border-radius: 8px;
    transition: 0.3s ease;
}

/* Button Hover */
.password-card .btn-primary:hover {
    background-color: #163a5c;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(15, 42, 68, 0.2);
}

/* Responsive */
@media (max-width: 576px) {
    .password-card {
        margin: 20px;
        padding: 25px;
    }
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
   
</div>
                <div class="row g-4 justify-content-center">
    <div class="col-md-6">
        <div class="password-card">
            <h4 style="text-align: center">Changing password form</h4>

            <form action="" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-3">
                    <label for="new_password" class="form-label">New password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
                
            </div>

            </div>

        </section>

    </div>

    <!-- FOOTER (ALWAYS BOTTOM) -->
    <footer class="bg-dark text-light py-3">
        <p class="text-center mb-0">&copy; 2025 ShamisTech. All Rights Reserved.</p>
    </footer>


 
    
    </div>


</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/profile.blade.php ENDPATH**/ ?>