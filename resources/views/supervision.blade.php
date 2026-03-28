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
                <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
                    

                   
                </div>

           
                <div class="col-md-5">
                    
                </div>
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
      <h6 class="alert-heading mb-1">Class represantative Information:  {{ $todayName }}</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

        <!-- Table Section -->
      <div class="table-responsive">

@if(isset($timetable) && count($timetable) > 0)

    @foreach($timetable as $courseName => $ntaLevels)

        {{-- COURSE TITLE --}}
        <div class="mb-5">
            <h3 class="text-center mb-4"
                style="color: green; font-family: 'Times New Roman', Times, serif;">
                TIME TABLE FOR {{ strtoupper($courseName) }}
            </h3>

            @foreach($ntaLevels as $ntaLevel => $semesters)

                {{-- NTA TITLE --}}
                <h4 style="color: darkblue;
                           font-family: 'Times New Roman', Times, serif;
                           text-align: center;
                           margin-top: 25px;">
                    NTA {{ $ntaLevel }}
                </h4>

                @foreach($semesters as $semesterName => $entries)

                    {{-- SEMESTER TITLE --}}
                    <h5 style="color: darkred; text-align: center; margin-bottom: 10px;font-family: 'Times New Roman', Times, serif">
                        Semester: {{ $semesterName }}
                    </h5>

                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start - End</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Room</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        @php
                            $groupedByDay = $entries->groupBy('day');
                        @endphp

                        @foreach($groupedByDay as $dayName => $entriesByDay)

                            @foreach($entriesByDay as $key => $entry)
                                <tr>

                                    {{-- Day with rowspan --}}
                                    @if($key === 0)
                                        <td rowspan="{{ count($entriesByDay) }}"
                                            style="writing-mode: vertical-lr;
                                                   text-align: center;
                                                   font-weight: bold;">
                                            {{ $dayName }}
                                        </td>
                                    @endif

                                    <td>
                                        {{ date('H:i', strtotime($entry->start_time)) }}
                                        -
                                        {{ date('H:i', strtotime($entry->end_time)) }}
                                    </td>

                                    <td>{{ $entry->subjectName }}</td>

                                    <td>
                                        {{ $entry->firstname }}
                                        {{ $entry->lastname }}
                                    </td>

                                    <td>{{ $entry->room_name }}</td>

                                    <td>
                                        @if($entry->status == "present")
                                            <span style="color: green;">
                                                Already Taught
                                            </span>
                                        @else
                                            <span style="color: red;">
                                                Subject Not Taught
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                        @endforeach

                        </tbody>
                    </table>

                @endforeach

            @endforeach
        </div>

    @endforeach

@else
    <p>No timetable for today.</p>
@endif

</div>

    </div>
</div>

<!-- JQuery for live search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val();

        $.ajax({
            url: "{{ route('teachers.index') }}",
            type: "GET",
            data: { search: query },
            beforeSend: function() {
                $('#teacherTableBody').html('<tr><td colspan="9" class="text-center"><div class="spinner-border text-primary" role="status"></div> Searching...</td></tr>');
            },
            success: function(data) {
                $('#teacherTableBody').html(data);
            },
            error: function() {
                $('#teacherTableBody').html('<tr><td colspan="9" class="text-danger text-center">Error loading data.</td></tr>');
            }
        });
    });
});
</script> --}}


@endsection
