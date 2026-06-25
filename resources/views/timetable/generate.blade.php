            @extends("layout.app")
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
                    .days{
                        writing-mode: vertical-rl;
                        font-weight: bold;
                        font-size: 20px;
                        text-align: center;
                    }
            </style>
            @section("content")
            <div id="content">
                    <div class="table-container p-3">
                        <div class="row mb-4">
                <div class="row align-items-center mb-4">
                
            <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                @if($systemTimetable->status != "created")
                <form id="generateForm" action="{{ route('generate.timetable') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="showLoading()">
                        <i class="fas fa-cog"></i> Generate Timetable
                    </button>
                </form>
                @endif

                {{-- <div class="mb-3">
                <a href="{{ route('timetable.download', 'Degree') }}" class="btn btn-success">
                    Download Degree Timetable
                </a>
                <a href="{{ route('timetable.download', 'Diploma') }}" class="btn btn-primary">
                    Download Diploma Timetable
                </a>
            </div> --}}

            </div>

            
                        

                    </button>
                    


                
            </div>
            <form action="{{ route('timetable.generate') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
            <select name="course" class="form-select">
                <option value="">-- Select Course --</option>

                <!-- Diploma Courses -->
                <optgroup label="Diploma Courses">
                @foreach($courses->where('course_level', 'Diploma') as $course)
                    <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                    {{ $course->courseName }}
                    </option>
                @endforeach
                </optgroup>

                <!-- Degree Courses -->
                <optgroup label="Degree Courses">
                @foreach($courses->where('course_level', 'Degree') as $course)
                    <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                    {{ $course->courseName }}
                    </option>
                @endforeach
                </optgroup>

            </select>
            </div>

                <div class="col-md-3">
                <select name="nta" class="form-select">
                    <option value="">-- Select NTA Level --</option>
                    @foreach($ntaLevels as $nta)
                    <option value="{{ $nta }}" {{ request('nta') == $nta ? 'selected' : '' }}>
                        {{ $nta }}
                    </option>
                    @endforeach
                </select>
                </div>

                <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
            </form>



                        <div class="alert alert-dismissible fade show flash-message" role="alert">
            <div class="d-flex align-items-center">
                
                <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
                <h6 class="alert-heading mb-1">Generate Timetable</h6>
                <p class="mb-0" style="color: green">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            </div>

            <div id="loading" style="display: none; margin-top: 20px;">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="ms-3">
                                            <h5 style="color: green;font-style: italic">Generating timetable, please wait...</h5>
                                            <p>This process may take several minutes. Do not refresh or close this page.</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="result" style="display: none; margin-top: 20px;"></div>

                        @foreach($timetableData as $timetable)

            <div class="mb-5">

            <h3 class="text-center mb-4" style="color: green;font-family: 'Times New Roman', Times, serif">

            TIME TABLE FOR {{ strtoupper($timetable['course1']) }} - {{ $timetable['nta_level'] }}

            @if(!empty($timetable['group_name']))
            ({{ $timetable['group_name'] }})
            @endif

            <br>

            <span style="font-size:16px;">
            SEMESTER: {{ strtoupper($timetable['semester']) }}

            @if(!empty($timetable['semester_year']))
            - {{ strtoupper($timetable['semester_year']) }}
            @endif

            </span>

            </h3>


            <div class="timetable-container">

            <table class="table table-bordered">

            <thead>
            <tr>
            <th>Day</th>
            <th>Start - End</th>
            <th>Subject</th>
            <th>Teacher</th>
            <th>Room</th>
            <th>Action</th>
            </tr>
            </thead>


            <tbody>

            @foreach($timetable['entries'] as $dayName => $entriesByDay)

            @php
            $totalRowsForDay = 0;


            foreach($entriesByDay as $entry){

            $start = \Carbon\Carbon::parse($entry->start_time);
            $end = \Carbon\Carbon::parse($entry->end_time);

            $creditHour = $entry->credit_hour ?? 3;

            $duration = $start->diffInHours($end);

            $totalRowsForDay += min($duration,$creditHour);
            }

            $dayPrinted = false;

            @endphp


            @foreach($entriesByDay as $entry)

            @php

            $start = \Carbon\Carbon::parse($entry->start_time);
            $end = \Carbon\Carbon::parse($entry->end_time);

            $creditHour = $entry->credit_hour ?? 3;

            $countHour = 0;

            @endphp


            @while($start < $end && $countHour < $creditHour)

            <tr>

            {{-- DAY COLUMN --}}
            @if(!$dayPrinted)

            <td rowspan="{{ $totalRowsForDay }}"
            style="writing-mode: vertical-lr;text-align:center;font-weight:bold;">

            {{ $dayName }}

            </td>

            @php $dayPrinted = true; @endphp

            @endif


            {{-- TIME SLOT --}}
            <td>

            {{ $start->format('H:i') }}

            -

            {{ $start->copy()->addHour()->format('H:i') }}

            </td>


            {{-- SUBJECT --}}
            <td>
                {{ $entry->subjectName }}

                @if(!empty($entry->subject_group_name))
                    @php
                        $courses = $groupCourses[$entry->subject_group_name] ?? [];
                    @endphp

                    <span class="badge bg-info text-dark">
                        Joined: {{ implode(' + ', $courses) }}
                    </span>
                @endif
            </td>
            {{-- TEACHER --}}
            <td>{{ $entry->firstname }} {{ $entry->middlename }} {{ $entry->lastname }}</td>


            {{-- ROOM --}}
            <td>{{ $entry->room_name }}</td>


            {{-- ACTION --}}
            <td>

<a href="{{ route('timetable.edit',$entry->timetable_id) }}"
class="btn btn-sm btn-outline-primary">
Edit
</a>
@if($systemTimetable->status == "created")
<button 
class="btn btn-sm btn-success mt-1"
onclick="openEmailModal(this)"

data-subject="{{ $entry->subjectName }}"
data-subject-code="{{ $entry->subjectCode }}"
data-teacher="{{ $entry->firstname }} {{ $entry->middlename }} {{ $entry->lastname }}"
data-teacher-email="{{ $entry->email ?? '' }}"

data-cr="{{ $entry->cr_name }} {{ $entry->cr_name2 }} {{ $entry->cr_name3 }}"
data-cr-email="{{ $entry->cr_email ?? '' }}"

data-course="{{ $timetable['course1'] }}"
data-nta="{{ $entry->nta_level }}"
data-semester="{{ $entry->semester_name }}"
>

Email
</button>
@endif

{{-- =========================================== --}}
{{-- NEW: SHIFT BUTTON                            --}}
{{-- Inafungua modal ya kubadilishana vipindi kati--}}
{{-- ya mwalimu huyu na mwalimu wa pili            --}}
{{-- =========================================== --}}
<button
type="button"
class="btn btn-sm btn-warning mt-1"
onclick="openShiftModal(this)"

data-timetable-id="{{ $entry->timetable_id }}"
data-teacher-id="{{ $entry->teacher_id ?? '' }}"
data-subject="{{ $entry->subjectName }}"
data-teacher="{{ $entry->firstname }} {{ $entry->middlename }} {{ $entry->lastname }}"
data-day="{{ $dayName }}"
data-time="{{ $start->format('H:i') }} - {{ $start->copy()->addHour()->format('H:i') }}"
data-room="{{ $entry->room_name }}"
>
<i class="bi bi-arrow-left-right"></i> Shift
</button>

</td>


            </tr>

            @php
            $start->addHour();
            $countHour++;
            @endphp

            @endwhile

            @endforeach

            @endforeach

            </tbody>

            </table>


            <!-- PRINT BUTTON -->

            <div class="text-center mt-3 nodis">

            <button
            class="btn btn-success btn-sm"
            onclick="printTimetable(this)"

            data-timetable='@json($timetable["entries"])'
            data-course="{{ $timetable['course'] }}"
            data-nta="{{ $timetable['nta_level'] }}"
            data-group="{{ $timetable['group_name'] ?? '' }}"
            data-semester="{{ $timetable['semester'] }}"
            data-year="{{ $timetable['semester_year'] ?? '' }}"
            >

            Print Timetable

            </button>

            </div>


            </div>

            </div>

            @endforeach


{{-- =========================================================== --}}
{{-- NEW: SHIFT MODAL (MOJA TU KWENYE UKURASA MZIMA)              --}}
{{-- SEHEMU MBILI: kushoto - Mwalimu wa Kwanza (uliyembonyeza),  --}}
{{-- kulia - Mwalimu wa Pili (unayemchagua + vipindi vyake).      --}}
{{-- =========================================================== --}}

@php
    // Fallback: kama controller haijapitisha $teachers kwenye view,
    // tunaipata moja kwa moja hapa (haitavunja ukurasa).
    $teachers = $teachers ?? \DB::table('teachers')
        ->where('branch_id', auth()->user()->branch_id)
        ->orderBy('firstname')
        ->get();
@endphp

<div class="modal fade" id="shiftModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">Badilishana Vipindi Kati ya Walimu Wawili</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="row">

          {{-- SEHEMU 1: MWALIMU WA KWANZA --}}
          <div class="col-md-6 mb-3">
            <h6 class="text-primary">Mwalimu wa Kwanza</h6>
            <div class="alert alert-secondary" id="shiftCurrentInfo"></div>
          </div>

          {{-- SEHEMU 2: MWALIMU WA PILI --}}
          <div class="col-md-6 mb-3">
            <h6 class="text-success">Mwalimu wa Pili</h6>

            <div class="mb-2">
              <label class="form-label">Chagua Mwalimu</label>
              <select id="secondTeacherSelect" class="form-select" onchange="loadTeacherPeriods()">
                <option value="">-- Chagua Mwalimu --</option>
                @foreach($teachers as $t)
                  <option value="{{ $t->id }}">
                    {{ $t->firstname }} {{ $t->middlename }} {{ $t->lastname }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-2">
              <label class="form-label">Chagua Kipindi cha Mwalimu Huyo</label>
              <select id="secondPeriodSelect" class="form-select">
                <option value="">-- Chagua mwalimu kwanza --</option>
              </select>
            </div>
          </div>

        </div>

        <hr>
        <div id="shiftAlertBox"></div>

        <input type="hidden" id="shiftTimetableId">

        <small class="text-muted">
          Mfumo utaangalia chumba, mwalimu, na darasa/kundi kabla ya kubadilishana,
          ili kuepuka migongano kwa pande zote mbili.
        </small>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Funga</button>
        <button type="button" class="btn btn-warning" onclick="confirmShift()">Badilishana</button>
      </div>

    </div>
  </div>
</div>

<script>
/**
 * Inafunguliwa wakati button ya "Shift" ya kipindi fulani inabonyezwa.
 * Inajaza taarifa za Mwalimu wa Kwanza (sehemu ya kushoto), na
 * inazima chaguo la kumchagua mwalimu huyu mwenyewe kwenye dropdown
 * ya Mwalimu wa Pili (asijibadilishe na yeye mwenyewe).
 */
function openShiftModal(btn) {
    document.getElementById('shiftTimetableId').value = btn.dataset.timetableId;

    document.getElementById('shiftCurrentInfo').innerHTML =
        `<strong>${btn.dataset.subject}</strong><br>` +
        `${btn.dataset.teacher}<br>` +
        `${btn.dataset.day} | ${btn.dataset.time} | Chumba: ${btn.dataset.room}`;

    document.getElementById('shiftAlertBox').innerHTML = '';

    const teacherSelect = document.getElementById('secondTeacherSelect');
    teacherSelect.value = '';
    Array.from(teacherSelect.options).forEach(opt => {
        opt.disabled = (opt.value !== '' && opt.value === btn.dataset.teacherId);
    });

    document.getElementById('secondPeriodSelect').innerHTML =
        '<option value="">-- Chagua mwalimu kwanza --</option>';

    new bootstrap.Modal(document.getElementById('shiftModal')).show();
}

/**
 * Mwalimu wa Pili akichaguliwa kwenye dropdown - inapakia (AJAX)
 * orodha ya vipindi vyake vyote ili mtumiaji achague kipi
 * cha kubadilishana nacho.
 */
function loadTeacherPeriods() {
    const teacherId = document.getElementById('secondTeacherSelect').value;
    const periodSelect = document.getElementById('secondPeriodSelect');
    const currentId = document.getElementById('shiftTimetableId').value;

    if (!teacherId) {
        periodSelect.innerHTML = '<option value="">-- Chagua mwalimu kwanza --</option>';
        return;
    }

    periodSelect.innerHTML = '<option value="">Inapakia...</option>';

    fetch(`{{ route('timetable.teacher.periods') }}?teacher_id=${teacherId}&exclude_id=${currentId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.entries || data.entries.length === 0) {
                periodSelect.innerHTML = '<option value="">Mwalimu huyu hana vipindi vingine</option>';
                return;
            }

            periodSelect.innerHTML = '<option value="">-- Chagua kipindi --</option>';

            data.entries.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.id;
                opt.textContent = `${e.day_name} | ${e.start_time}-${e.end_time} | ${e.room_name} | ${e.subject_name}`;
                periodSelect.appendChild(opt);
            });
        })
        .catch(() => {
            periodSelect.innerHTML = '<option value="">Imeshindwa kupakia vipindi</option>';
        });
}

/**
 * Inatuma ombi la kubadilishana (swap) kati ya kipindi cha Mwalimu wa
 * Kwanza na kipindi kilichochaguliwa cha Mwalimu wa Pili. Server
 * itaangalia migongano kabla ya kufanya mabadiliko.
 */
function confirmShift() {
    const targetId = document.getElementById('secondPeriodSelect').value;
    const alertBox = document.getElementById('shiftAlertBox');

    if (!targetId) {
        alertBox.innerHTML = '<div class="alert alert-warning">Tafadhali chagua mwalimu wa pili na kipindi chake.</div>';
        return;
    }

    alertBox.innerHTML = '<div class="alert alert-info">Inakagua migongano, tafadhali subiri...</div>';

    fetch(`{{ route('timetable.swap') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            timetable_id_1: document.getElementById('shiftTimetableId').value,
            timetable_id_2: targetId
        })
    })
    .then(res => res.json().then(data => ({ status: res.status, body: data })))
    .then(({ status, body }) => {
        if (status === 200 && body.success) {
            alertBox.innerHTML = '<div class="alert alert-success">' + body.message + '</div>';
            setTimeout(() => location.reload(), 1200);
        } else {
            let errHtml = '<div class="alert alert-danger"><strong>' +
                (body.message || 'Imeshindikana kubadilishana.') + '</strong>';

            if (body.errors && body.errors.length) {
                errHtml += '<ul class="mb-0 mt-2">';
                body.errors.forEach(e => errHtml += `<li>${e}</li>`);
                errHtml += '</ul>';
            }
            errHtml += '</div>';
            alertBox.innerHTML = errHtml;
        }
    })
    .catch(() => {
        alertBox.innerHTML = '<div class="alert alert-danger">Hitilafu ya mtandao/server imetokea.</div>';
    });
}
</script>

                        

                        
                    </div>
                </div>
                <script>
function openEmailModal(btn){

    // SET TEXT
    document.getElementById('modalSubject').innerText = btn.dataset.subject;
    document.getElementById('form_subject_code').value = btn.dataset.subjectCode;
    document.getElementById('modalTeacher').innerText = btn.dataset.teacher;
    document.getElementById('modalCR').innerText = btn.dataset.cr || 'N/A';

    // SET FORM VALUES
    document.getElementById('form_subject').value = btn.dataset.subject;
    document.getElementById('form_teacher_email').value = btn.dataset.teacherEmail;
    document.getElementById('form_cr_email').value = btn.dataset.crEmail;

    // OPEN MODAL
    let modal = new bootstrap.Modal(document.getElementById('emailModal'));
    modal.show();
}
</script>


            <script>
            function showLoading() {
                document.getElementById('loading').style.display = 'block';
                document.querySelector('button[type="submit"]').disabled = true;
                
                // Submit form via AJAX
                event.preventDefault();
                
                const form = document.getElementById('generateForm');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    
                    if (data.success) {
                        document.getElementById('result').innerHTML = `
                            <div class="alert alert-success">
                                <h5>Timetable Generated Successfully!</h5>
                                <p>${data.message}</p>
                            </div>
                        `;
                    } else {
                        document.getElementById('result').innerHTML = `
                            <div class="alert alert-success">
                                <h5>Timetable Generated Successfully!</h5>
                                <p>${data.message}</p>
                            </div>
                        `;
                        // document.getElementById('result').innerHTML = `
                        //     <div class="alert alert-danger">
                        //         <h5>Error Generating Timetable</h5>
                        //         <p>${data.message}</p>
                        //         <button class="btn btn-warning" onclick="window.location.reload()">Try Again</button>
                        //     </div>
                        // `;
                    }
                    document.getElementById('result').style.display = 'block';
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('result').innerHTML = `
                        <div class="alert alert-danger">
                            <h5>Error Generating Timetable</h5>
                            <p>${error.message}</p>
                            <button class="btn btn-warning" onclick="window.location.reload()">Try Again</button>
                        </div>
                    `;
                    document.getElementById('result').style.display = 'block';
                });
            }
            </script>
            <script>
            function printTimetable(button) {

                const timetableData = JSON.parse(button.getAttribute("data-timetable"));

                const courseName = button.getAttribute("data-course") || "";
                
                const ntaLevel = button.getAttribute("data-nta") || "";
                const groupName = button.getAttribute("data-group") || "";
                const semester = button.getAttribute("data-semester") || "";
                const active = button.getAttribute("data-active") || "";

                // ===== Get & sort unique timeslots =====
                let timeslots = [];

                for (let day in timetableData) {
                    timetableData[day].forEach(e => {

                        const slot = `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}`;

                        if (!timeslots.includes(slot)) {
                            timeslots.push(slot);
                        }

                    });
                }

                timeslots.sort((a, b) => {
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

                            @page { margin:0 }

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

                        <h2>THE INSTITUTE OF PUBLIC AND ADMINISTRATION</h2><br>
                        <span style="font-style: italic">Generated on
                            ${
                                new Date().toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric'
                                })
                            }
                        </span>

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
                                    ${timeslots.map(slot => `<th>${slot}</th>`).join('')}
                                </tr>

                            </thead>

                            <tbody>

                                ${days.map(day => `

                                    <tr>

                                        <td>${day.toUpperCase()}</td>

                                        ${timeslots.map(slot => {

                                            const entry = timetableData[day].find(e =>
                                                `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}` === slot
                                            );

                                            if(entry){

                                                return `
                                                    <td>

                                                        <strong>${entry.subjectName}</strong><br>

                                                        ${entry.firstname} ${entry.middlename} ${entry.lastname}<br>

                                                        ${entry.room_name}

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

            <!-- CSS -->
            <style>
            .nodis { display: block; }
            @media print {
                .nodis { display: none !important; }
            }
            </style>

@endsection