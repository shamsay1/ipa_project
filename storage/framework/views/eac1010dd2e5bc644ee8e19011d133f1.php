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

<!-- HERO -->
<section class="hero">

<img src="<?php echo e(asset('images/ipalogo1.png')); ?>" width="100">

<h2>INSTITUTE OF PUBLIC AND<br> ADMINISTRATION</h2>

<h3>Course: <?php echo e($cr->course->courseName); ?></h3>

<p>
Mfumo wa kitaalamu wa kusimamia ratiba za masomo kwa vyuo na taasisi za elimu.
</p>

</section>

</div>

<!-- FOOTER -->
<footer>
<p>&copy; 2025 ShamisTech. All Rights Reserved.</p>
</footer>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/studentDash.blade.php ENDPATH**/ ?>