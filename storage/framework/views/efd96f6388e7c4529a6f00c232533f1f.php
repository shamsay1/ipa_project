
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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

        /* Styles for conflicts display */
        .conflicts-container {
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .conflict-item {
            padding: 12px 15px;
            border-left: 4px solid #dc3545;
            margin-bottom: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
            color: #721c24;
            animation: fadeIn 0.5s ease-in-out;
        }
        .conflict-item.warning {
            border-left-color: #ffc107;
            background-color: #fff3cd;
            color: #856404;
        }
        .conflict-item.info {
            border-left-color: #17a2b8;
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .conflict-item i {
            margin-right: 8px;
        }
        .no-conflicts {
            padding: 12px 15px;
            border-left: 4px solid #28a745;
            margin-bottom: 10px;
            background-color: #d4edda;
            border-radius: 4px;
            color: #155724;
            animation: fadeIn 0.5s ease-in-out;
        }
        .conflicts-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
</style>
<?php $__env->startSection("content"); ?>
 <div id="content">
    <div class="table-container p-3">

        <div class="conflicts-container mt-4">
    <h6 class="conflicts-title">
        <i class="bi bi-lightbulb me-2"></i> Alternative timetable suggestion
    </h6>
    <div id="solutionsList">
        <div class="conflict-item info">
            <span class="loading-spinner"></span> Finding the best solution...
        </div>
    </div>
</div>

        <div class="table-container p-3">
            <div class="row mb-4">
                <div class="row align-items-center mb-4">
                    <div class="conflicts-container mt-4">
                <h6 class="conflicts-title">
                    <i class="bi bi-exclamation-triangle me-2"></i> Check Timetable Conflicts
                </h6>
                <div id="conflictsList">
                    <div class="no-conflicts">
                        <i class="bi bi-check-circle-fill"></i> No conflict detected
                    </div>
                </div>
            </div>
                </div>
            </div>

          

            <form id="editForm" action="<?php echo e(route('timetable.update', $timetable->id)); ?>" method="post">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="teacher_id" class="form-label">Teachers Name</label>
            <select name="teacher_id" id="teacher_id" class="form-control" oninput="checkConflicts()">
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($subject->id); ?>" <?php echo e($timetable->subject_id == $subject->id ? "selected" : ""); ?>>
                    <?php echo e($subject->teacher->firstname); ?> <?php echo e($subject->teacher->lastname); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="room_id" class="form-label">Room Name</label>
            <select name="room_id" id="room_id" class="form-control" oninput="checkConflicts()">
                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($room->id); ?>" <?php echo e($timetable->room_id == $room->id ? "selected" : ""); ?>>
                    <?php echo e($room->name); ?> 
                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="day_id" class="form-label">Day Name</label>
            <select name="day_id" id="day_id" class="form-select" oninput="checkConflicts()">
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($day->id); ?>" <?php echo e($timetable->day_id == $day->id ? "selected" : ""); ?>>
                    <?php echo e($day->day_name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="timeslot_id" class="form-label">Time of Session</label>
            <select name="timeslot_id" id="timeslot_id" class="form-select" oninput="checkConflicts()">
                <?php $__currentLoopData = $time; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ti->id); ?>" <?php echo e($timetable->timeslot_id == $ti->id ? "selected" : ""); ?>>
                    <?php echo e($ti->start_time); ?> - <?php echo e($ti->end_time); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    

    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
        <button type="submit" id="btnUpdate" class="btn btn-success">
            Update Timetable
        </button>
    </div>
</form>

<script>
function loadSolutions() {
    const timetableId = <?php echo e($timetable->id); ?>;
    const subjectId = document.getElementById('teacher_id').value;
    const roomId = document.getElementById('room_id').value;

    const container = document.getElementById('solutionsList');
    container.innerHTML = `
        <div class="conflict-item info">
            <span class="loading-spinner"></span> Find the best solutions...
        </div>
    `;

    fetch('<?php echo e(route("timetable.checkSolutions")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ timetable_id: timetableId, subject_id: subjectId, room_id: roomId })
    })
    .then(res => res.json())
    .then(data => {
        container.innerHTML = '';

        if (data.solutions.length === 0) {
            container.innerHTML = `
                <div class="conflict-item warning">No solutions found.</div>
            `;
        } else {
            data.solutions.slice(0,130).forEach(opt => { // Onyesha 50 za mwanzo
                container.innerHTML += `
                    <div class="no-conflicts">
                        <i class="bi bi-check-circle-fill"></i>
                        ${opt.day_name} (${opt.slot_time}) - ${opt.room_name}
                    </div>
                `;
            });
        }

        
        checkConflictsForButton();
    });
}

// Function ya kuchunguza conflicts na ku-enable/disable button
function checkConflictsForButton() {
    const timetableId = <?php echo e($timetable->id); ?>;
    const subjectId = document.getElementById('teacher_id').value;
    const roomId = document.getElementById('room_id').value;
    const dayId = document.getElementById('day_id').value;
    const timeslotId = document.getElementById('timeslot_id').value;

    fetch('<?php echo e(route("timetable.checkConflicts")); ?>', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
        },
        body: JSON.stringify({
            timetable_id: timetableId,
            subject_id: subjectId,
            room_id: roomId,
            day_id: dayId,
            timeslot_id: timeslotId
        })
    })
    .then(res => res.json())
    .then(data => {
        const btn = document.getElementById('btnUpdate');
        btn.disabled = !data.can_update; 
        const conflictsDiv = document.getElementById('conflicts');
        if(conflictsDiv){
            conflictsDiv.innerHTML = data.conflicts.length > 0 ? data.conflicts.join('<br>') : '';
        }
    })
    .catch(err => console.error(err));
}
function loadAvailableRooms() {
    const dayId = document.getElementById('day_id').value;
    const timeslotId = document.getElementById('timeslot_id').value;
    const timetableId = <?php echo e($timetable->id); ?>;

    const roomSelect = document.getElementById('room_id');
    const selectedRoom = roomSelect.value; 

    if (!dayId || !timeslotId) return;

    fetch('<?php echo e(route("timetable.availableRooms")); ?>', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
        },
        body: JSON.stringify({
            day_id: dayId,
            timeslot_id: timeslotId,
            timetable_id: timetableId
        })
    })
    .then(res => res.json())
    .then(data => {

        roomSelect.innerHTML = '<option value="">-- Select Room --</option>';

        let roomStillExists = false;

        data.rooms.forEach(room => {
            let selected = "";

            // 👉 kama room ya zamani bado ipo basi ibaki selected
            if (room.id == selectedRoom) {
                selected = "selected";
                roomStillExists = true;
            }

            roomSelect.innerHTML += `
                <option value="${room.id}" ${selected}>
                    ${room.name} - capacity ${room.capacity}
                </option>
            `;
        });

        // 👉 kama room ya zamani haipo kwenye free rooms
        if (selectedRoom && !roomStillExists) {
            roomSelect.innerHTML += `
                <option value="${selectedRoom}" selected>
                    (Previously Selected - Not Available)
                </option>
            `;
        }

    });
}
loadAvailableRooms();

document.getElementById('day_id').addEventListener('change', loadAvailableRooms);
document.getElementById('timeslot_id').addEventListener('change', loadAvailableRooms);
// Run once on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSolutions();

    // Hii itafanya update kila user anapo-change day au timeslot
    document.getElementById('day_id').addEventListener('input', checkConflictsForButton);
    document.getElementById('timeslot_id').addEventListener('input', checkConflictsForButton);
    document.getElementById('teacher_id').addEventListener('input', loadSolutions);
    document.getElementById('room_id').addEventListener('input', loadSolutions);
});
</script>

           

            
            <!-- Conflicts display area -->
            
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function(){
    $('.selectpicker').selectpicker();
});
</script>

    <script>
        let debounceTimer;
        
        function checkConflicts() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const timetableId = <?php echo e($timetable->id); ?>;
                const subjectId = document.getElementById('teacher_id').value;
                const roomId = document.getElementById('room_id').value;
                const dayId = document.getElementById('day_id').value;
                const timeslotId = document.getElementById('timeslot_id').value;

                // Show loading state
                const conflictsList = document.getElementById('conflictsList');
                conflictsList.innerHTML = `
                    <div class="conflict-item info">
                        <span class="loading-spinner"></span> Finding the conflicts...
                    </div>
                `;

                fetch('<?php echo e(route("timetable.checkConflicts")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        timetable_id: timetableId,
                        subject_id: subjectId,
                        room_id: roomId,
                        day_id: dayId,
                        timeslot_id: timeslotId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    conflictsList.innerHTML = '';
                    
                    if (data.conflicts && data.conflicts.length > 0) {
                        data.conflicts.forEach(conflict => {
                            const conflictItem = document.createElement('div');
                            conflictItem.className = 'conflict-item';
                            conflictItem.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${conflict}`;
                            conflictsList.appendChild(conflictItem);
                        });
                    } else {
                        conflictsList.innerHTML = `
                            <div class="no-conflicts">
                                <i class="bi bi-check-circle-fill"></i> No conflict detected.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    conflictsList.innerHTML = `
                        <div class="conflict-item warning">
                            <i class="bi bi-exclamation-triangle-fill"></i> Hitilafu imetokea wakati wa kuangalia migongano.
                        </div>
                    `;
                });
            }, 500); // Debounce for 500ms to avoid too many requests
        }

        // Check conflicts on page load with current values
        document.addEventListener('DOMContentLoaded', function() {
            checkConflicts();
        });
    </script>
    

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/timetableEdit.blade.php ENDPATH**/ ?>