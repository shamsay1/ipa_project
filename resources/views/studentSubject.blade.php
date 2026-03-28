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
<a class="nav-link" href="{{ url('/studentDash') }}">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ url('/studentSub') }}">Subjects</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ url('/studenttbl') }}">Timetable</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ url('/lessons') }}">Lessons</a>
</li>

<li class="nav-item">
<form method="POST" action="{{ route('logout') }}">
@csrf
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

        <section class="container mt-5">
            
          

            <!-- TABLE SCROLLABLE WRAPPER -->
            <div class="table-scroll shadow">
                <div style="text-align: center;margin-top: 10px;">
                <img src="{{ asset('images/ipalogo1.png') }}" alt="" width="120">
            </div>
     <h3 style="text-align: center;margin-top: 10px">INSTITUTE OF PUBLIC AND ADMINISTRATION</h3>
                {{-- <h5 style="text-align: center">TEACHER SUBJECT: <b>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</b></h5><br> --}}
              <div class="table-responsive">
                <table class="table table-hover">
    <thead>
        <tr style="background-color: #0f2a44">
            <th colspan="100" style="text-align:center;font-weight:bold;color:black">
                Subjects for {{ $courseName }} - {{ $nta1 }}
            </th>
        </tr>

        <tr>
            <th>T/N</th>
            <th>Name</th>
            <th>Code</th>
            <th>Teacher's Name</th>
        </tr>
    </thead>

    <tbody>

        @forelse ($subjects as $i => $subject)

        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $subject->subjectName }}</td>
            <td>{{ $subject->subjectCode }}</td>
            <td>{{ $subject->teacher->firstname }} {{ $subject->teacher->middlename }} {{ $subject->teacher->lastname }}</td>
        </tr>

        @empty

        <tr>
            <td colspan="5" class="text-center">No subjects found</td>
        </tr>

        @endforelse

    </tbody>
</table>
            </div>

            </div>

        </section>

    </div>
    <div class="modal fade" id="confirmPresentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('teacher.attendance.store') }}">
        @csrf

        <input type="hidden" name="timetable_id" id="modal_timetable_id">

        <div class="modal-header">
          <h5 class="modal-title">Confirm Attendance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            Are you sure you want to mark this lesson as Present?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-success">
            Confirm
          </button>
        </div>

      </form>

    </div>
  </div>
</div>


<!-- FOOTER -->
<footer>
<p>&copy; 2025 ShamisTech. All Rights Reserved.</p>
</footer>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>