<table border="1" cellspacing="0" cellpadding="5">

    {{-- ROW 1: NTA --}}
    <tr>
        <th rowspan="3">Day</th>
        <th rowspan="3">Time</th>

        @php
        $ntaLevels = $timetable->pluck('nta_level')
            ->unique()
            ->sortBy(fn($nta)=> (int) preg_replace('/\D/', '', $nta))
            ->values();
        @endphp

        @foreach($ntaLevels as $nta)
            @php
                $courses = $timetable->where('nta_level', $nta)
                    ->pluck('courseName')
                    ->unique();
            @endphp

            {{-- colspan = jumla ya (courses × groups_in_course) --}}
            @php
                $span = 0;
                foreach($courses as $course){
                    $span += $timetable
                        ->where('nta_level', $nta)
                        ->where('courseName', $course)
                        ->pluck('group_name')
                        ->unique()
                        ->count();
                }
            @endphp

            <th colspan="{{ $span }}">{{ $nta }}</th>
        @endforeach
    </tr>

    {{-- ROW 2: COURSE --}}
    <tr>
        @foreach($ntaLevels as $nta)
            @php
                $courses = $timetable->where('nta_level', $nta)
                    ->pluck('courseName')
                    ->unique();
            @endphp

            @foreach($courses as $course)
                @php
                    $groups = $timetable
                        ->where('nta_level', $nta)
                        ->where('courseName', $course)
                        ->pluck('group_name')
                        ->unique();
                @endphp

                <th colspan="{{ max($groups->count(),1) }}">{{ $course }}</th>
            @endforeach
        @endforeach
    </tr>

    {{-- ROW 3: GROUP --}}
    <tr>
        @foreach($ntaLevels as $nta)
            @php
                $courses = $timetable->where('nta_level', $nta)
                    ->pluck('courseName')
                    ->unique();
            @endphp

            @foreach($courses as $course)
                @php
                    $groups = $timetable
                        ->where('nta_level', $nta)
                        ->where('courseName', $course)
                        ->pluck('group_name')
                        ->unique();
                @endphp

                @if($groups->count() > 0)
                    @foreach($groups as $group)
                        <th>{{ $group }}</th>
                    @endforeach
                @else
                    <th>-</th>
                @endif
            @endforeach
        @endforeach
    </tr>

    {{-- BODY --}}
    @php
        $days = $timetable->pluck('day_name')->unique();
        $slots = $timetable->map(fn($item)=>[
            'start'=>$item->start_time,
            'end'  =>$item->end_time
        ])->unique()->sortBy('start')->values();
    @endphp

    @foreach($days as $day)
        @foreach($slots as $slot)
            <tr>
                @if ($loop->first)
                    <td rowspan="{{ count($slots) }}"><b>{{ strtoupper($day) }}</b></td>
                @endif

                <td>{{ $slot['start'] }} - {{ $slot['end'] }}</td>

                @foreach($ntaLevels as $nta)
                    @php
                        $courses = $timetable->where('nta_level', $nta)
                            ->pluck('courseName')
                            ->unique();
                    @endphp

                    @foreach($courses as $course)
                        @php
                            $groups = $timetable
                                ->where('nta_level', $nta)
                                ->where('courseName', $course)
                                ->pluck('group_name')
                                ->unique();
                        @endphp

                        @foreach($groups as $group)
                            @php
                                $cell = $timetable->first(function ($item) use ($day, $slot, $course, $nta, $group) {
                                    return $item->day_name == $day
                                        && $item->start_time == $slot['start']
                                        && $item->end_time == $slot['end']
                                        && $item->courseName == $course
                                        && $item->nta_level == $nta
                                        && $item->group_name == $group;
                                });
                            @endphp

                            <td>
                                @if($cell)
                                    <b>{{ $cell->subjectName }}</b><br>
                                    Room: {{ $cell->room }}
                                @endif
                            </td>
                        @endforeach

                        {{-- If no grouping existed --}}
                        @if($groups->count() == 0)
                            <td></td>
                        @endif

                    @endforeach
                @endforeach
            </tr>
        @endforeach
    @endforeach
</table>
