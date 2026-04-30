@extends("layout.app")

<style>
    .table-container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            padding: 0.75rem;
        }
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
        }
        .progress {
            height: 8px;
        }
        .btn-export {
            background-color: #0d6efd;
            color: white;
        }
        .btn-export:hover {
            background-color: #0b5ed7;
            color: white;
        }
        .action-btn {
            color: #6c757d;
            padding: 0.25rem 0.5rem;
            font-size: 1.2rem;
        }
        .action-btn:hover {
            color: #0d6efd;
        }
        .flash-message {
    background-color: #d1e7dd; /* Light green background */
    border-color: #badbcc; /* Darker green border */
    color: #0f5132; /* Dark green text */
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-in-out;
}

.flash-message .alert-heading {
    color: #0f5132;
    font-weight: bold;
}

.flash-message .btn-close {
    color: #0f5132;
    opacity: 0.8;
}

.flash-message .bi-check-circle-fill {
    font-size: 1.5rem;
    color: #28a745; /* Deeper green for the icon */
}

/* Optional: Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
        
        /* Styles for submenu */
        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .submenu.show {
            max-height: 500px;
        }
        .submenu .nav-link {
            padding: 0.5rem 0.8rem 0.5rem 2rem;
            font-size: 0.85rem;
        }
        .has-submenu::after {
            content: '\f282';
            font-family: 'Bootstrap Icons';
            float: right;
            transition: transform 0.3s;
        }
        .has-submenu.collapsed::after {
            transform: rotate(-90deg);
        }
</style>
@section("content")
<div id="content">
    <div class="table-container p-3">
        <div class="row mb-4">
            <h3 style="text-align: center;font-family: 'Times New Roman', Times, serif">THE INSTITUTES OF PUBLIC AND ADMINISTRATION(IPA)</h3>
        <h5 style="font-family: 'Times New Roman', Times, serif;text-align: center;font-size: 24px">Teaching Timetable for Teacher <span style="font-family: 'Times New Roman', Times, serif;text-align: center;color: green;font-size: 25px">{{ strtoupper($teacher->firstname . ' ' . $teacher->middlename. ' ' . $teacher->lastname) }}</span></h5>
        
        
        <table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Day</th>
            <th>Start - End</th>
            <th>Subject</th>
            <th>Course</th>
            <th>Room</th>
        </tr>
    </thead>

    <tbody>
@forelse($groupedEntries as $day => $entries)
    @foreach($entries as $key => $entry)
        <tr>

            {{-- DAY --}}
            @if($key === 0)
                <td rowspan="{{ count($entries) }}" 
                    class="align-middle"
                    style="writing-mode: vertical-lr; font-weight:bold;">
                    {{ $day }}
                </td>
            @endif

            {{-- TIME --}}
            <td>
                {{ date('H:i', strtotime($entry->start_time)) }} -
                {{ date('H:i', strtotime($entry->end_time)) }}
            </td>

            {{-- SUBJECT --}}
            <td>{{ $entry->subject_display }}</td>
            {{-- COURSE (FINAL OUTPUT) --}}
            <td>{{ $entry->course_display }}</td>

            {{-- ROOM --}}
            <td>{{ $entry->room_name }}</td>

        </tr>
    @endforeach

@empty
    <tr>
        <td colspan="5">No timetable found for this teacher.</td>
    </tr>
@endforelse
    </tbody>
</table>

<button 
                class="btn btn-primary"
                onclick="printTeacherTimetable(this)"

                data-timetable='@json($timetable["entries"])'
                data-timeslots='@json($timetable["timeslots"])'
                data-teacher="{{$teacher->firstname}} {{$teacher->middlename}} {{$teacher->lastname}}"
                >

                <i class="fas fa-print"></i> Print Timetable

                </button>

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
        <a href="{{ route('teacher.load.report1') }}" class="btn btn-secondary btn-sm">← Back</a>





@endsection
