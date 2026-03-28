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

.timetable-table{
width:100%;
border-collapse:collapse;
min-width:700px;
}

.timetable-table th,
.timetable-table td{
border:1px solid #ddd;
padding:8px;
text-align:center;
font-size:14px;
vertical-align:middle;
}

.timetable-table th{
background:#0f2a44;
color:white;
}

/* SUBJECT STYLES */

.subject{
font-weight:bold;
font-size:13px;
}

.course{
font-size:12px;
color:#475569;
}

.teacher{
font-size:12px;
color:#334155;
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

.timetable-table{
font-size:12px;
}

.subject{
font-size:12px;
}

}

</style>
</head>

<body>

<div class="page-wrapper">

<!-- HEADER -->

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

@foreach($entries as $semester => $semesterEntries)

<h4 class="mt-4 text-center">{{ $semester }} Timetable</h4>

<div id="print-{{ $semester }}">

<table class="timetable-table">

<thead>

<tr>

<th>DAY / TIME</th>

@foreach($timeslots as $slot)

<th>

{{ date('H:i', strtotime($slot['start'])) }}
<br>
{{ date('H:i', strtotime($slot['end'])) }}

</th>

@endforeach

</tr>

</thead>

<tbody>

@foreach($semesterEntries as $day => $dayEntries)

<tr>

<td>
{{ strtoupper($day) }}
</td>

@foreach($timeslots as $slot)

<td>

@php
$found = $dayEntries
->where('start_time',$slot['start'])
->where('end_time',$slot['end'])
->first();
@endphp

@if($found)

<div class="subject">
{{ $found->subjectName }}
({{ $found->subjectCode }})
</div>

@if($found->group_name)

<div class="course">
GROUP {{ strtoupper($found->group_name) }}
</div>

@endif

<div class="teacher">
{{ $found->firstname }} {{ $found->lastname }}
</div>
<div style="font-weight: bold">
{{ $found->room_name }}
</div>

@endif

</td>

@endforeach

</tr>

@endforeach

</tbody>

</table>

</div>

<button 
    onclick="printTimetable(this)" 
    class="btn btn-primary mt-3 mb-5"

    data-timetable='@json($semesterEntries)' 

    data-course="{{ $semesterEntries->first()->first()->courseName ?? '' }}"

    data-nta="{{ $semesterEntries->first()->first()->nta_level ?? '' }}"

    data-group="{{ $semesterEntries->first()->first()->group_name ?? '' }}"

    data-semester="{{ $semester }}"

    data-active=""
>
    Print {{ $semester }}
</button>

@endforeach

</div>
</div>

</section>

</div>

<!-- FOOTER -->

<footer>
<p>&copy; 2025 ShamisTech. All Rights Reserved.</p>
</footer>

</div>

<!-- MODAL -->



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

function printTimetable(button) {

    const timetableData = JSON.parse(button.getAttribute("data-timetable") || '{}');

    const courseName = button.getAttribute("data-course") || "";
    const ntaLevel = button.getAttribute("data-nta") || "";
    const groupName = button.getAttribute("data-group") || "";
    const semester = button.getAttribute("data-semester") || "";
    const active = button.getAttribute("data-active") || "";

    // ===== Get unique timeslots =====
    let timeslots = [];

    for (let day in timetableData) {

        timetableData[day].forEach(e => {

            const slot = `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}`;

            if (!timeslots.includes(slot)) {
                timeslots.push(slot);
            }

        });

    }

    // ===== Sort timeslots =====
    timeslots.sort((a,b)=>{
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

        @page{margin:0}

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

        ${timeslots.map((slot,index)=>`
<th>

<div style="font-size: 30px;">${index+1}</div>

<br>

${slot}

</th>
`).join('')}

        </tr>

        </thead>

        <tbody>

        ${days.map(day=>`

            <tr>

            <td>${day.toUpperCase()}</td>

            ${timeslots.map(slot=>{

                const entry = timetableData[day].find(e =>
                    `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}` === slot
                );

                if(entry){

                    return `
                    <td>

                    <strong>${entry.subjectName}</strong><br>

                    ${entry.firstname} ${entry.lastname}<br>

                    <strong>${entry.room_name}</strong>

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

</body>
</html>