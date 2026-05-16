@extends("layout.app")

<style>
/* NORMAL VIEW */
table {
    font-size: 14px;
}

/* PRINT MODE */
@media print {

    /* Ficha kila kitu */
    body * {
        visibility: hidden;
    }

    /* Onesha table tu */
    #mytable, #mytable * {
        visibility: visible;
    }

    #mytable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    table {
        font-size: 12px;
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 2px !important;
    }

    .title {
        font-size: 11px;
        margin: 3px;
    }

    td div {
        font-size: 12px;
        line-height: 1.1;
    }

    @page {
        size: A4 landscape;
        margin: 5mm;
    }
}
</style>

@section("content")

<div id="content">

    <!-- FILTER FORM -->
    <div class="table-container p-3 no-print">
        <form method="GET" action="">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Select Room</label>
                    <select name="room_id" class="form-control" required>
                        <option value="">-- Select Room --</option>
                        @foreach($allRooms as $room)
                            <option value="{{ $room->id }}">
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mt-4">
                    <button class="btn btn-primary mt-2">
                        Generate
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($report && $report['selectedRoom'])

    <!-- REPORT -->
    <div class="report-container" id="mytable">

        <h4 class="text-center title">
            INSTITUTE OF PUBLIC AND ADMINISTRATION
        </h4>

        <h4 class="text-center text-success title">
            Room Timetable ({{ $report['selectedRoom']->name }})
        </h4>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Day / Time</th>

                    @foreach($timeslots as $slot)
                        <th>
                            {{ $slot->start_time }} <br> - <br> {{ $slot->end_time }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($days as $day)
                <tr>
                    <td style="font-weight:bold;">
                        {{ $day->day_name }}
                    </td>

                    @foreach($timeslots as $slot)

                        @php
                            $data = $report['usageMap'][$day->id][$slot->id] ?? null;
                        @endphp

                        <td style="min-width:120px;">

                            @if($data)
                                <div>
                                    <strong style="color:#0d6efd;">
                                        {{ $data['teacher'] }}
                                    </strong>
                                    <br>

                                    <span style="color:#198754;">
                                        {{ $data['subject'] }}
                                    </span>

                                    @if($data['course'])
                                        <br>
                                        <small style="color:#6c757d;">
                                            {{ $data['course'] }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">
                                    Free
                                </span>
                            @endif

                        </td>

                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @endif

    <!-- PRINT BUTTON -->
    <div class="mt-3 text-center no-print">
        <button class="btn btn-success" onclick="myPrint()">
            Print Report
        </button>
    </div>

</div>

<script>
function myPrint() {
    window.print();
}
</script>

@endsection