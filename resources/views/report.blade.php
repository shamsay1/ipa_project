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
    <div class="col-md-6">
        
    </div>

    
</div>


            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Report</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('teacher.load.report1') }}" method="GET" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label for="department" class="form-label">Select a Department</label>
                <select name="department_id" id="department" class="form-select">
                    <option value="">-- All Departments --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->deptName }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4">
    <label for="semester" class="form-label">Select a Semester</label>
    <select name="semester" id="semester" class="form-select">
        <option value="">-- All Semesters --</option>
        @foreach($semesters as $sem)
            <option value="{{ $sem->id }}" {{ request('semester') == $sem->id ? 'selected' : '' }}>
                {{ $sem->academic_year }} - {{ $sem->semName }}
            </option>
        @endforeach
    </select>
</div>

            
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter Report</button>
            </div>
        </form>
    </div>
</div>

<hr>

@if(isset($report))
    <div id="printArea" class="report-container">
        <!-- Heading -->
        
        <div class="report-header text-center mb-4">
            <h2 style="font-family: 'Times New Roman', Times, serif">INSTITUTE PF PUBLIC AND ADMINISTRATION</h2>
            <h4>Teacher Workload Report</h4>
        </div>

        <table class="table table-bordered report-table text-center">
    <thead>
        <tr>
            <th>Teacher Name</th>
            <th>Total Periods/Week</th>
            <th>Max per Day</th>
            <th>Evening Lessons</th>
            <th>Full Day Load</th> <!-- NEW -->
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($report as $row)
            <tr>
                <td style="text-align: left">{{ $row['teacher'] }}</td>
                <td>{{ $row['total_periods'] }}</td>
                <td>{{ $row['max_per_day'] }}</td>
                <td>{{ $row['evening_lessons'] }}</td>
                <td>{{ $row['full_day'] }}</td> <!-- NEW -->
                <td>
                    <a href="{{ route('teacher.timetable', ['id' => $row['teacher_id']]) }}"
                       class="btn btn-sm btn-outline-primary">
                        View all
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No data found for this filter.</td>
            </tr>
        @endforelse
    </tbody>
</table>


        <!-- Footer -->
        <div class="report-footer text-center mt-4">
            <p style="font-family: 'Times New Roman', Times, serif;font-style: italic;font-szie: 18px">
                 <strong>
                    This report shows the workload of teachers
                    @if(request()->filled('department_id') && isset($departments))
                        in {{ $departments->firstWhere('id', request()->department_id)->deptName ?? '' }} Department
                    @endif
                </strong>
            </p>
        </div>
    </div>

    <div class="mt-3 text-center">
        <button class="btn btn-primary" onclick="window.print()">Print Report</button>
    </div>

    <style>
        /* General styling */
        .report-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }

        .report-header h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .report-header h4 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .report-table th {
            background: #007bff;
            color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .report-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .report-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .report-footer {
            font-size: 14px;
            margin-top: 20px;
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #printArea, #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                border: none;
                box-shadow: none;
            }

            button {
                display: none !important;
            }
        }
    </style>
@endif






            

            
        </div>
    </div>

@endsection