<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Timetables</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
        }

        h3 {
            text-align: center;
            color: green;
            margin-bottom: 5px;
        }

        .semester-text {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
        }

        .day-col {
            writing-mode: vertical-lr;
            text-align: center;
            font-weight: bold;
        }

        .badge {
            background: #17a2b8;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 4px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

@foreach($timetableData as $timetable)

    <!-- HEADER -->
    <h3>
        TIME TABLE FOR {{ strtoupper($timetable['course1']) }} - {{ $timetable['nta_level'] }}

        @if(!empty($timetable['group_name']))
            ({{ $timetable['group_name'] }})
        @endif
    </h3>

    <div class="semester-text">
        SEMESTER: {{ strtoupper($timetable['semester']) }}

        @if(!empty($timetable['semester_year']))
            - {{ strtoupper($timetable['semester_year']) }}
        @endif
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Start - End</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Room</th>
            </tr>
        </thead>

        <tbody>

        @foreach($timetable['entries'] as $dayName => $entriesByDay)

            @php
                $totalRowsForDay = 0;

                foreach($entriesByDay as $entry){
                    $start = \Carbon\Carbon::parse($entry->start_time);
                    $end = \Carbon\Carbon::parse($entry->end_time);
                    $creditHour = $entry->credit_hour ?? 3;
                    $duration = $start->diffInHours($end);

                    $totalRowsForDay += min($duration, $creditHour);
                }

                $dayPrinted = false;
            @endphp

            @foreach($entriesByDay as $entry)

                @php
                    $start = \Carbon\Carbon::parse($entry->start_time);
                    $end = \Carbon\Carbon::parse($entry->end_time);
                    $creditHour = $entry->credit_hour ?? 3;
                    $countHour = 0;
                @endphp

                @while($start < $end && $countHour < $creditHour)

                <tr>

                    <!-- DAY -->
                    @if(!$dayPrinted)
                        <td rowspan="{{ $totalRowsForDay }}" class="day-col">
                            {{ $dayName }}
                        </td>
                        @php $dayPrinted = true; @endphp
                    @endif

                    <!-- TIME -->
                    <td>
                        {{ $start->format('H:i') }} -
                        {{ $start->copy()->addHour()->format('H:i') }}
                    </td>

                    <!-- SUBJECT -->
                    <td>
                        {{ $entry->subjectName }}

                        @if(!empty($entry->subject_group_name))
                            @php
                                $courses = $groupCourses[$entry->subject_group_name] ?? [];
                            @endphp

                            @if(count($courses))
                                <br>
                                <span class="badge">
                                    Joined: {{ implode(' + ', $courses) }}
                                </span>
                            @endif
                        @endif
                    </td>

                    <!-- TEACHER -->
                    <td>
                        {{ $entry->firstname }}
                        {{ $entry->middlename }}
                        {{ $entry->lastname }}
                    </td>

                    <!-- ROOM -->
                    <td>{{ $entry->room_name }}</td>

                </tr>

                @php
                    $start->addHour();
                    $countHour++;
                @endphp

                @endwhile

            @endforeach

        @endforeach

        </tbody>
    </table>

    <div class="page-break"></div>

@endforeach

</body>
</html>