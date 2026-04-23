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
        

        <!-- Add New Teacher -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="bi bi-plus me-2"></i>Add Time slot
        </button>
    </div>

    
</div>

<!-- Modal for Adding Teacher -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addTeacherModalLabel">Add Time slot</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="{{ route('timeslot.store')}}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="firstname" class="form-label">Start Time</label>
                    <input type="time" class="form-control" name="start_time" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mobile" class="form-label">End Time</label>
                    <input type="time" class="form-control" name="end_time" required>
                </div>
                
                
            </div>
        </div>
        
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
      
    </div>
  </div>
</div>
            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Time Slot Details</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>START TIME</th>
                            <th>END TIME</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    @php $i = 1; @endphp
    @forelse ($timeslot as $time)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $time->start_time }}</td>
            <td>{{ $time->end_time }}</td>
     
            <td>
    <!-- Edit button -->
    <a href="{{ route('timeslot.edit', $time->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bi bi-pencil-square"></i>
    </a>

    <!-- Delete button with confirmation -->
    <form action="{{ route('timeslot.destroy', $time->id) }}" method="POST" style="display:inline;" 
          onsubmit="return confirm('Are you sure you want to delete this timeslot?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-outline-danger" title="Delete">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td>


        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">No Record Found</td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>

            
        </div>
    </div>

@endsection