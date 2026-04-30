<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Arial; }
h3 { text-align:center; color:green; }

table {
    width:100%;
    border-collapse: collapse;
    margin-bottom:40px;
}

th,td {
    border:1px solid #000;
    font-size:10px;
    padding:4px;
}

th {
    background:#0f2a44;
    color:#fff;
}

.teacher-box {
    page-break-after: always;
}
</style>
</head>
<body>

@foreach($allData as $data)

<div class="teacher-box">

<h3>
TIMETABLE FOR TEACHER
<br>
{{ $data['teacher']->firstname }} {{ $data['teacher']->middlename }} {{ $data['teacher']->lastname }}
</h3>

<table>
<thead>
<tr>
<th>DAY / TIME</th>

@foreach($data['timeslots'] as $slot)
<th>
{{ date('H:i', strtotime($slot->start_time)) }} - {{ date('H:i', strtotime($slot->end_time)) }}
</th>
@endforeach

</tr>
</thead>

<tbody>

@foreach($data['entries'] as $day => $dayEntries)

<tr>
<td><b>{{ strtoupper($day) }}</b></td>

@foreach($data['timeslots'] as $slot)

<td>

@php
$found = $dayEntries
    ->where('start_time',$slot->start_time)
    ->where('end_time',$slot->end_time)
    ->first();
@endphp

@if($found)

@if($found->group_name)
<b>{{ $found->group_name }}</b><br>
@endif

{{ $found->subjectName }}<br>
{{ $found->fullCourseName }}<br>
ROOM: {{ $found->room_name }}

@endif

</td>

@endforeach

</tr>

@endforeach

</tbody>
</table>

</div>

@endforeach

</body>
</html>