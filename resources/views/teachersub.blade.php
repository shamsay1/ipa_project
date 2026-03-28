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

    <div class="col-md-6">
        <!-- Search Box -->
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search">
            <button class="btn btn-outline-secondary" type="button">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>
</div>


            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Teacher Subject Report</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('teacher.load.report') }}" method="GET" class="row g-3">
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
    <div id="mytable" class="report-container">
    <!-- Logo -->
    

    <!-- Header -->
    <div class="report-header text-center mb-4">
        <h3 style="font-family: 'Times New Roman', Times, serif; margin-bottom: 5px;text-align: center">
            INSTITUTE OF PUBLIC AND ADMINISTRATION
        </h3>
        <h5 style="font-family: 'Times New Roman', Times, serif; margin-bottom: 5px;text-align: center">
            TEACHER'S SUBJECTS REPORT
        </h5>
        <p style="font-family: 'Times New Roman', Times, serif; font-style: italic;text-align: center">
            Academic Year {{ date('Y') }}/{{ date('Y') + 1 }}
        </p>
    </div>

    <!-- Table -->
    <table class="report-table text-center">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Teacher's Name</th>
                <th>Total Subjects</th>
                <th>Status</th>
                <th class="nodis">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left">{{ strtoupper($row->teacher_name) }}</td>
                    <td>{{ $row->subject_count }}</td>
                    <td>
                        @if($row->status == 'Overloaded')
                            <span class="badge bg-danger">{{ $row->status }}</span>
                        @elseif($row->status == 'Balanced')
                            <span class="badge bg-success">{{ $row->status }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $row->status }}</span>
                        @endif
                    </td>
                    <td class="nodis"><a href="{{ route('teacher.load.report',$row->id) }}" style="text-decoration: none;">View All</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer text-center mt-4">
        <p style="font-family: 'Times New Roman', Times, serif; font-style: italic;text-align: center">
            <strong>
                This report shows the number of subjects of the teachers
                @if(request()->filled('department_id') && isset($departments))
                    in {{ $departments->firstWhere('id', request()->department_id)->deptName ?? '' }} Department
                @endif
            </strong>
        </p>
        <p class="disno">
            Signature<strong> ...................................</strong>
            <br><br>
            Head of Department
            
        </p>
    </div>
</div>

<!-- Print Button -->
<div class="mt-3 text-center">
    <button class="btn btn-primary" onclick="myPrint()">Print Report</button>
</div>

<!-- CSS -->
<style>
    body {
        background: #f8f9fa;
    }

    .report-container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        max-width: 900px;
        margin: auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    

    .report-header h3 {
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .report-header h5 {
        font-size: 18px;
        text-decoration: underline;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 15px;
        margin-top: 15px;
    }

    .report-table th,
    .report-table td {
        border: 1px solid #000;
        padding: 8px;
    }

    .report-table th {
        background-color: #e9ecef;
        text-transform: uppercase;
    }

    .report-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .report-footer {
        margin-top: 25px;
        font-size: 15px;
    }

    
</style>
<script>
  function myPrint() {
      var content = document.getElementById("mytable").outerHTML;
      var printWindow = window.open("", "_blank", "width=1000,height=1200");
      printWindow.document.write(`
          <html>
          <head>
              <title></title>
              <style>
                  @page {
                      margin: 0;
                  }
                  body {
                      margin: 40px;
                      padding: 0;
                      background: white;
                  }
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin: 0;
                      padding: 0;
                  }
                 .nodis{
                   display: none;
                 
                 }
                  th, td {
                      border: 0.5px solid black;
                      color: black !important;
                      padding: 3px;
                      margin: 0;
                      text-align: center;
                  }
                  th {
                      background: #e0e0e0;
                  }
                  h4{
                    text-align: center;
                    color: black !important;
                  
                  }
              </style>
          </head>
          <body onload="window.print(); window.close();">
              ${content}
          </body>
          </html>
      `);
      printWindow.document.close();
  }
  </script>

@endif






            

            
        </div>
    </div>

@endsection