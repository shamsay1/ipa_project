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
            <div class="row align-items-center mb-4">
                

            </div>
        </div>
        <div class="container">

 

    <!-- Display Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style: none">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>
         <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Subjects Performances</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <!-- Table Section -->
        <div class="table-responsive">
           <form action="{{ route('report7') }}" method="GET" class="row g-2 mb-3">

    <div class="col-md-3">
        <select name="course_id" class="form-control">
            <option value="">-- Select Course --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" 
                    {{ ($selectedCourse ?? '') == $course->id ? 'selected' : '' }}>
                    {{ $course->courseName }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="nta_level" class="form-control">
            <option value="">-- Select NTA --</option>
            @foreach(["NTA-4","NTA-5","NTA-6","NTA-7","NTA-8"] as $nta)
                <option value="{{ $nta }}" {{ ($selectedNta ?? '') == $nta ? 'selected' : '' }}>
                    {{ $nta }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="semester_id" class="form-control">
            <option value="">-- Select Semester --</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester->id }}" 
                    {{ ($selectedSemester ?? '') == $semester->id ? 'selected' : '' }}>
                    {{ $semester->semName }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control">
    </div>

    <div class="col-md-2">
        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control">
    </div>

    <div class="col-md-1">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>

</form>
  
   <table class="table table-hover">
    <thead class="table-light">
        <tr>
            <th>T/N</th>
            <th>Subject Name</th>
            <th>Subject Code</th>
            <th>Teacher' Name</th>
            <th>Total Taught</th>
            <th>Not Taught</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td style="text-align: left">{{ $row->subjectName }}</td>
            <td>{{ $row->subjectCode }}</td>
            <td style="text-align: left">{{ $row->firstname }} {{ $row->middlename }} {{ $row->lastname }}</td>

            <td class="text-success fw-bold">{{ $row->total_taught ?? 0 }}</td>
            <td class="text-danger fw-bold">{{ $row->total_not_taught ?? 0 }}</td>
            <td>
                <span class="badge bg-info">
                    {{ $row->percentage ?? 0 }} %
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-danger">
                No Data Found
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

    

    
</div>
<div class="mt-3 nodis">
    <button onclick="printReport()" class="btn btn-success">
        Print Report
    </button>
</div>

    </div>
</div>

<script>
    function formatDate(dateString) {

    if(!dateString) return '';

    const date = new Date(dateString);

    const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };

    return date.toLocaleDateString('en-GB', options);
}
function printReport() {

    const table = document.querySelector(".table").outerHTML;

    
    let courseShortName = "{{ $selectedCourse ? $courses->where('id',$selectedCourse)->first()->short_name ?? 'ALL' : 'ALL' }}";
    let start_date = formatDate(@json($startDate ?? ''));
    let end_date = formatDate(@json($endDate ?? ''));
    let ntaLevel = "{{ $selectedNta ?? 'ALL NTA' }}";
    let semester = "{{ $selectedSemester ? $semesters->where('id',$selectedSemester)->first()->semName ?? '' : '' }}";

    
    let ntaPrefix = '';
    switch (ntaLevel) {
        case "NTA-4": ntaPrefix = 'BTC'; break;
        case "NTA-5": ntaPrefix = 'TC'; break;
        case "NTA-6": ntaPrefix = 'OD'; break;
        case "NTA-7": ntaPrefix = 'HD'; break;
        case "NTA-8": ntaPrefix = 'B'; break;
        default: ntaPrefix = '';
    }

    
    let semesterRoman = '';
    if (semester.includes('1')) {
        semesterRoman = 'I';
    } else if (semester.includes('2')) {
        semesterRoman = 'II';
    }

    
    let fileName = `Subject_report_${ntaPrefix}${courseShortName}_${semesterRoman}.pdf`;

    const printWindow = window.open('', '', 'width=1000,height=800');

    printWindow.document.write(`
        <html>
        <head>
            <title>${fileName}</title>
            <style>
                @page { margin: 0; }
                body {
                    margin: 40px;
                    background: white;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                }
                h2, h4, h5 {
                    text-align: center;
                    margin: 3px 0;
                    line-height: 1.4;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
                    margin-top: 25px;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 6px;
                    text-align: center;
                    vertical-align: middle;
                }
                th {
                    background-color: #e9ecef;
                    text-transform: uppercase;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background: #f8f9fa;
                }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            <div style="display: flex;justify-content: center;margin-bottom: 6px;">
                <img src="{{ asset('images/ipalogo1.png') }}" width="180px" height="140px">
            </div>
            <h2>THE INSTITUTE OF PUBLIC AND ADMINISTRATION</h2>
            <h4>SUBJECTS PERFORMANCE REPORT</h4>
            <h5>
                Course: ${ntaPrefix}${courseShortName.toUpperCase()}${semesterRoman} | 
                NTA Level: ${ntaLevel.toUpperCase()}
            </h5>
            <h5>This report shows the subjects Performances from ${start_date} to ${end_date}</h5>
            ${table}
        </body>
        </html>
    `);

    printWindow.document.close();
}
</script>

@endsection
