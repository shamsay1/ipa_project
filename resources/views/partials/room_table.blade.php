@php $i = 1; @endphp

@forelse ($rooms as $room)
<tr>
    <td>{{ $i++ }}</td>
    <td>{{ $room->name }}</td>
    <td>{{ $room->capacity }}</td>
    <td>{{ $room->type }}</td>
    <td>{{ $room->practical_type }}</td>
    <td>{{ $room->status }}</td>
    <td>
    <!-- Edit button -->
    <a href="{{ route('room.edit', $room->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bi bi-pencil-square"></i>
    </a>

    <!-- Delete button with confirmation -->
    <form action="{{ route('room.destroy', $room->id) }}" method="POST" style="display:inline;" 
          onsubmit="return confirm('Are you sure you want to delete this room?');">
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
