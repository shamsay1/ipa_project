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
            <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                <form action="{{ route('timetable.solveConflicts') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">
            Solve Conflicts Automatically
        </button>
    </form>
     <form action="{{ route('reduce.evening') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">
        Reduce evening session
    </button>
</form>
   <form action="{{ route('sync.group.subjects') }}" method="POST">
@csrf

<button type="submit" class="btn btn-primary">
Sync Shared Subjects
</button>

</form>
        
    </div>


            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">conflicts Validation details</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
           <h3 style="text-align: center;font-family: 'Times New Roman', Times, serif;color: green">Timetable Validation Report</h3>
    <p><strong style="color: green">Overall Score: <span class="fw-bold">{{ $score }}%</span></strong></p>
        @php
        $i = 1;
    @endphp
    <div class="text-center mt-4">
    




</div>

    @foreach($reports as $rule => $violations)
    
        <div style="margin-bottom: 20px;">
            
            <h4 style="font-family: 'Times New Roman', Times, serif"><span>{{ $i++}}. </span> {{ ucfirst(str_replace('_', ' ', $rule)) }}</h4>
            @if(count($violations) === 0)
                <p style="color: green;font-weight: bold;font-style: italic"> No conflicts</p>
            @else
                <p style="color: red;font-weight: bold;font-style: italic">Found {{ count($violations) }} conflicts</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach((array) $violations[0] as $col => $val)
                                <th>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($violations as $row)
                            <tr>
                                @foreach((array) $row as $val)
                                    <td>{{ $val }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach

            
        </div>
    </div>

@endsection