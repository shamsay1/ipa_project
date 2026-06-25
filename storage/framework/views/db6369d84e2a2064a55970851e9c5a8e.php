<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Timetable Management System</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family: Arial, Helvetica, sans-serif;
}

body{
background:#f8fafc;
color:#1e293b;
}

/* PAGE STRUCTURE */
.page-wrapper{
min-height:100vh;
display:flex;
flex-direction:column;
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

.page-content{
flex:1;
}

/* NAVBAR */
.navbar{
background:#0f2a44;
border-bottom:4px solid #c9a227;
}

.navbar-brand{
font-weight:bold;
}

.nav-link{
color:white !important;
}

.nav-link:hover{
color:#c9a227 !important;
}

/* HERO */

.hero{
background:linear-gradient(to right,#0f2a44,#143a5e);
color:white;
text-align:center;
padding:70px 20px;
}

.hero h2{
font-size:30px;
margin-top:10px;
}

.hero h3{
font-size:20px;
margin-top:10px;
}

.hero p{
max-width:600px;
margin:auto;
margin-top:10px;
color:#e5e7eb;
}

/* FOOTER */

footer{
background:#0f2a44;
color:#e5e7eb;
text-align:center;
padding:15px;
margin-top:auto;
}

/* MOBILE */

@media(max-width:768px){

.hero h2{
font-size:22px;
}

.hero h3{
font-size:18px;
}

}

</style>

</head>

<body>

<div class="page-wrapper">

<!-- HEADER -->
<header>

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container-fluid">

<a class="navbar-brand" href="#">
Timetable Management System
</a>

<!-- HAMBURGER -->
<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarMenu">

<span class="navbar-toggler-icon"></span>

</button>

<!-- MENU -->
<div class="collapse navbar-collapse" id="navbarMenu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/studentDash')); ?>">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/studentSub')); ?>">Subjects</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/studenttbl')); ?>">Timetable</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo e(url('/lessons')); ?>">Lessons</a>
</li>
<li class="nav-item">
<a class="nav-link" href="<?php echo e(route('studentprofile')); ?>">Settings</a>
</li>

<li class="nav-item">
<form method="POST" action="<?php echo e(route('logout')); ?>">
<?php echo csrf_field(); ?>
<button class="btn btn-warning ms-lg-3 mt-2 mt-lg-0">
Logout
</button>
</form>
</li>

</ul>

</div>
</div>

</nav>

</header>

 <div class="page-content">

        <section class="container mt-5 bg-white">
            
          

            <!-- TABLE SCROLLABLE WRAPPER -->
            <div class="table-scroll shadow">
                <div style="text-align: center;margin-top: 10px;">
                <img src="<?php echo e(asset('images/ipalogo1.png')); ?>" alt="" width="120">
            </div>
     <h3 style="text-align: center;margin-top: 10px">INSTITUTE OF PUBLIC AND ADMINISTRATION</h3>
                <h5 style="text-align: center">STUDENT NAME: <b><?php echo e(Auth::guard('cr')->user()->firstname); ?> <?php echo e(Auth::guard('cr')->user()->middlename); ?> <?php echo e(Auth::guard('cr')->user()->lastname); ?></b></h5><br>
              <div class="table-responsive">
                <div class="text-end mt-3 nodis">
   
</div>
                <div class="row g-4 justify-content-center">
    <div class="col-md-6">
        <div class="password-card">
            <h4 style="text-align: center">Changing password form</h4>

             <form action="<?php echo e(route('profile.changePassword1')); ?>" method="POST">
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
                    <input type="password" name="confirm_password" id="new_password_confirmation" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Change password</button>
                </div>
            </form>
        </div>
    </div>
</div>
                
            </div>

            </div>

        </section>

    </div>


<!-- FOOTER -->
<footer>
<p>&copy; 2025 ShamisTech. All Rights Reserved.</p>
</footer>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(session('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '<?php echo e(session('success')); ?>',
    confirmButtonText: 'OK'
});
</script>

<?php endif; ?>
<?php if(session('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '<?php echo e(session('error')); ?>',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>
</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/profilestudent.blade.php ENDPATH**/ ?>