<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Generated Timetable</h4>
                </div>

                <div class="card-body">
                    @if(empty($timetableData))
                        <div class="alert alert-info">
                            No timetable data found. Please generate a timetable first.
                        </div>
                        <a href="{{ route('timetable.generate') }}" class="btn btn-primary">
                            Generate Timetable
                        </a>
                    @else
                        @foreach($timetableData as $timetable)
                            <div class="mb-5">
                                <h3 class="text-center mb-4">TIME TABLE FOR {{ strtoupper($timetable['course']) }} - {{ $timetable['nta_level'] }}</h3>
                                
                                <div class="table-responsive">
                                    <table border="1" cellpadding="10px" cellspacing="0">
                                        <thead>
                                            <tr class="table-dark">
                                                <th>DAYS</th>
                                                <th>TIME</th>
                                                <th>SUBJECT</th>
                                                <th>TEACHER'S NAME</th>
                                                <th>MOBILE NO</th>
                                                <th>ROOM NO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($timetable['entries'] as $dayName => $entriesByDay)
                                                {{-- Tumia rowspan kwa entry ya kwanza ya kila siku --}}
                                                @foreach($entriesByDay as $key => $entry)
                                                    <tr>
                                                        {{-- Ongeza rowspan kwa seli ya siku --}}
                                                        @if($key === 0)
                                                            <td rowspan="{{ count($entriesByDay) }}"><strong>{{ $dayName }}</strong></td>
                                                        @endif
                                                        <td>{{ date('H:i', strtotime($entry->start_time)) }} - {{ date('H:i', strtotime($entry->end_time)) }}</td>
                                                        <td>{{ $entry->subjectName }}</td>
                                                        <td>{{ $entry->firstname }} {{ $entry->lastname }}</td>
                                                        <td>{{ $entry->mobile }}</td>
                                                        <td>{{ $entry->room_name }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>