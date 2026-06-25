<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Timetable Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial, Helvetica, sans-serif;
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

/* TABLE */

.table-scroll{
overflow-x:auto;
background:#fff;
border-radius:8px;
padding:15px;
}

table{
min-width:700px;
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

table{
font-size:13px;
}

}

</style>
</head>

<body>

<div class="page-wrapper">

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container-fluid">

<a class="navbar-brand" href="#">
Timetable Management System
</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarMenu">

<span class="navbar-toggler-icon"></span>

</button>

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
<a class="nav-link" href="{{ route('studentprofile') }}">Settings</a>
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

<div class="page-content">

<section class="container mt-5">

<div class="table-scroll shadow">

<div class="text-center mb-3">
<img src="{{ asset('images/ipalogo1.png') }}" width="120">
</div>

<h3 class="text-center mb-4">
INSTITUTE OF PUBLIC AND ADMINISTRATION
</h3>

<div class="table-responsive">

    @if(isset($holidayMessage))
    <div class="alert alert-warning text-center">
        {{ $holidayMessage }}
    </div>
    @else
       <table class="table table-hover table-bordered">

    <thead>

        <tr style="background:#0f2a44;color:white">
            <th colspan="100" class="text-center">Today's Lessons</th>
        </tr>

        <tr>
            <th>T/N</th>
            <th>Subject</th>
            <th>Code</th>
            <th>Timeslot</th>
            <th>Room</th>
            <th>Teacher</th>
            <th>Status</th>
            <th>Action</th>
            <th>Emergency</th>
        </tr>

    </thead>

    <tbody>

        @php $i = 1; @endphp

        @forelse($lessons as $lesson)

        <tr>

            <td>{{ $i++ }}</td>

            <td>{{ $lesson->subjectName }}</td>

            <td>{{ $lesson->subjectCode ?? '-' }}</td>

            <td>
                {{ $lesson->start_time }} - {{ $lesson->end_time }}
            </td>

            <td>{{ $lesson->room_name ?? 'N/A' }}</td>

            <td>
                {{ $lesson->firstname ?? '' }} {{ $lesson->middlename ?? '' }} {{ $lesson->lastname ?? '' }}
            </td>

            <td>
                @if($lesson->status == 'present')
                    <span class="badge bg-success">Present</span>
                @elseif ($lesson->status == 'emergency')
                    <span class="badge bg-info">Emergency</span>
                @else
                    <span class="badge bg-danger">Absent</span>
                @endif
            </td>

            <td>

@if($lesson->status == 'absent')

    <!-- Attend -->
    <button class="btn btn-success btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#confirmPresentModal"
        data-id="{{ $lesson->timetable_id }}">
        Attend
    </button>

   


@else

    <button class="btn btn-success btn-sm" disabled>
        Present
    </button>

@endif

</td>
<td>
    @if($lesson->status == 'present')
    <button class="btn btn-success btn-sm" disabled>
        Present
    </button>
    @else
    <button class="btn btn-warning btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#emergencyModal"
        data-id="{{ $lesson->timetable_id }}">
        Emergency
    </button>
    @endif
</td>


        </tr>

        @empty

        <tr>
            <td colspan="100%" class="text-center">
                No lessons available
            </td>
        </tr>

        @endforelse

    </tbody>

</table>
    @endif

</div>
<hr>

<div class="table-responsive mt-4">

    <h4 class="text-center" style="color:red; font-weight:bold;">
        Emergency Lessons (Pending Make-Up)
    </h4>

    <table class="table table-bordered table-hover">

        <thead style="background:#8b0000; color:white;">
            <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Code</th>
                <th>Teacher</th>
                <th>NTA</th>
                <th>Emergency Date</th>
                <th>Days Remaining</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        @forelse($emergencyLessons as $index => $lesson)

            @php
                $daysPassed = (int) \Carbon\Carbon::parse($lesson->date)->diffInDays(now());
                $daysRemaining = 5 - $daysPassed;
            @endphp

            <tr>

                <td>{{ $index + 1 }}</td>

                <td>{{ $lesson->subjectName }}</td>

                <td>{{ $lesson->subjectCode ?? '-' }}</td>

                <td>
                    {{ $lesson->firstname }} {{ $lesson->lastname }}
                </td>

               

                <td>{{ $lesson->nta_level }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                </td>

                <td>
                    @if($daysRemaining > 0)
                        <span class="badge bg-warning text-dark">
                            {{ $daysRemaining }} days left
                        </span>
                    
                    @endif
                </td>
                <td>
                    <button 
                        class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmPresentModal"
                        data-id="{{ $lesson->timetable_id }}">
                        Mark Present
                    </button>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="100%" class="text-center text-muted">
                    No emergency lessons for your course and NTA
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>


</div>

</section>

</div>

{{-- Modal1 --}}
<div class="modal fade" id="emergencyModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Mark Emergency</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('teacher.emergency') }}" method="POST">
@csrf

<div class="modal-body">

<input type="hidden" name="timetable_id" id="emergency_timetable_id">


</div>

<div class="modal-footer">
<button type="submit" class="btn btn-warning">
Confirm Emergency
</button>
</div>

</form>

</div>
</div>
</div>


<!-- MODAL -->

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

var modal = document.getElementById('confirmPresentModal');

modal.addEventListener('show.bs.modal', function (event) {

var button = event.relatedTarget;

var id = button.getAttribute('data-id');

document.getElementById('modal_timetable_id').value = id;

});

});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    var emergencyModal = document.getElementById('emergencyModal');

    emergencyModal.addEventListener('show.bs.modal', function (event) {

        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');

        document.getElementById('emergency_timetable_id').value = id;
    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    confirmButtonText: 'OK'
});
</script>
@endif


</body>
</html>