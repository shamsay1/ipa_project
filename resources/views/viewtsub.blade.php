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




           <div class="table-responsive">
   
        <p style="text-align: center"><strong>Department:</strong> {{ $teacher->department->deptName }}</p>
        <div>
            <h5 class="mb-0 text-center" style="color: green;font-wweight: bold;font-family: 'Times New Roman', Times, serif">Subjects Taught by Teacher {{ strtoupper($teacher->firstname) }} {{ strtoupper($teacher->lastname) }}</h5>
        </div>

           
              
                
                   <table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Subject Name</th>
            <th>Courses</th>
            <th>Subject Code</th>
        </tr>
    </thead>
    <tbody>
      
        @foreach($subjects as $index => $sub)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="text-align:left">
                    {{ strtoupper($sub['subjectName']) }}
                </td>
                <td>
                    {{ implode(' + ', $sub['courses']) }}
                </td>

                <td>{{ $sub['subjectCode'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
            
          
            <div class="text-end mt-3">
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">← Back</a>
            </div>

    
</div>


            
        </div>
    </div>

@endsection