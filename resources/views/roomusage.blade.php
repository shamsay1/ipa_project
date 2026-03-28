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
  <form method="GET" action="">
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Select Room</label>
            <select name="room_id" class="form-control" required>
                <option value="">-- Select Room --</option>
                @foreach($allRooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Report Type</label>
            <select name="report_type" class="form-control" required>
                <option value="room">Room Usage</option>
            </select>
        </div>

        <div class="col-md-3 mt-4">
            <button class="btn btn-primary mt-2">Generate</button>
        </div>
    </div>
</form>


    <hr>

   @if($reportType === 'room' && $report && $report['selectedRoom'])
<div class="report-container" id="mytable">


    <h4 style="text-align:center;font-family:'Times New Roman'">INSTITUTE OF PUBLIC AND ADMINISTRATION</h4>
    <h4 style="text-align:center;color:green;font-family: 'Times New Roman', Times, serif">
        Room Usage ({{ $report['selectedRoom']->name }})  
        — Active Semester: {{ $activeSemester->semName ?? 'None' }}
    </h4>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th style="padding: 0;">Day</th>
                <th>Time Slot</th>
                <th>{{ $report['selectedRoom']->name }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($days as $dayIndex => $day)
                @foreach($timeslots as $slot)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{ count($timeslots) }}" style="writing-mode: vertical-lr; font-weight:bold;">
                            {{ $day->day_name }}
                        </td>
                    @endif

                    <td>{{ $slot->start_time }} - {{ $slot->end_time }}</td>

                    @php
                        $status = $report['usageMap'][$day->id][$slot->id] ?? 'Free';
                    @endphp

                    <td>
                        @if($status === 'Used')
                            <span class="badge bg-danger">Used</span>
                        @else
                            <span class="badge bg-success">Free</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="mt-3 text-center">
    <button class="btn btn-primary" onclick="myPrint()">Print Report</button>
</div>

<script>
function myPrint() {
    const printContent = document.getElementById('mytable').innerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
</script>

    </div>

@endsection