@php $i = 1; @endphp
@forelse ($teachers as $teacher)
<tr>
    <td>{{ $i++ }}</td>
    <td>{{ $teacher->firstname }} {{ $teacher->middlename }} {{ $teacher->lastname }}</td>
    <td>{{ $teacher->gender }}</td>

    <td>{{ $teacher->email }}</td>
    <td>{{ $teacher->mobile }}</td>
    {{-- <td>{{ $teacher->teacher_code }}</td> --}}
    <td>{{ $teacher->role}}</td>
    <td>{{ $teacher->status }}</td>
    <td>
        <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>

        <form action="{{ $teacher->status == 'Active' ? route('teachers.block', $teacher->id) : route('teachers.unblock', $teacher->id) }}" 
              method="POST" style="display:inline;" 
              onsubmit="return confirm('Are you sure you want to {{ $teacher->status == 'Active' ? 'block' : 'unblock' }} this teacher?');">
            @csrf
            @method('PATCH')
            <button class="btn btn-sm {{ $teacher->status == 'Active' ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                    title="{{ $teacher->status == 'Active' ? 'Block' : 'Unblock' }}">
                <i class="bi {{ $teacher->status == 'Active' ? 'bi-slash-circle-fill' : 'bi-check-circle-fill' }}"></i>
            </button>
        </form>
        <a href="{{ route('teacher.subjects',$teacher->id) }}" style="text-decoration: none;">View subjects</a>

    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center">No teachers found</td>
</tr>
@endforelse
