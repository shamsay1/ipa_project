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
            <i class="bi bi-plus me-2"></i>Add Semester
        </button>
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

<!-- Modal for Adding Teacher -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="addTeacherModalLabel">Add Semester</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="{{ route('semester.store')}}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="firstname" class="form-label">Semester Name</label>
                    <input type="text" class="form-control" name="semName" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mobile" class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mobile" class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>
                
                
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="">Select Academic Year</label>
                    <select name="ac_year" class="form-control">
                        <option value="">--Select Academic Year--</option>
                        @php
                        $currentYear = date("Y");
        for ($i = 0; $i < 5; $i++) {
            $start = $currentYear - $i;
            $end = $start + 1;
            echo "<option value='$start/$end'>$start/$end</option>";
                 }
                    @endphp
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="mobile" class="form-label">Semester Code</label>
                    <input type="text" class="form-control" name="semCode" required>
                    
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
      <h6 class="alert-heading mb-1">Semester Details</h6>
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
                            <th>Semester Name</th>
                            <th>Academic Year</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Semester Code</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    @php $i = 1; @endphp
    @forelse ($sems as $se)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $se->semName }}</td>
            <td>{{ $se->academic_year }}</td>
            <td>{{ $se->start_date }}</td>
            <td>{{ $se->end_date }}</td>
            <td>{{ $se->semCode }}</td>
            <td>
                @if ($se->status=="Active")
                   <span style="color: green">{{ $se->status }}</span>
                   @else
                    <span style="color: red">{{ $se->status }}</span>
                    
                @endif

            </td>
            <td>
                <a href="" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
    {{-- Set Active --}}
    <!-- Button (trigger modal) -->
<a href="#" 
   class="btn btn-sm btn-success" 
   data-bs-toggle="modal" 
   data-bs-target="#confirmModal" 
   data-url="{{ route('semester.changeStatus', ['id' => $se->id, 'status' => 'Active']) }}"
   title="Set Active">
    <i class="bi bi-check-circle-fill"></i>
</a>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Do you want to confirm  <strong>to be active for this semester?</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmBtn" class="btn btn-success">Confirm</a>
      </div>
    </div>
  </div>
</div>

<!-- Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var confirmModal = document.getElementById('confirmModal');
    var confirmBtn = document.getElementById('confirmBtn');

    confirmModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button iliyobofya
        var url = button.getAttribute('data-url'); // Pata link halisi ya action
        confirmBtn.setAttribute('href', url); // Weka kwenye confirm button
    });
});
</script>

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