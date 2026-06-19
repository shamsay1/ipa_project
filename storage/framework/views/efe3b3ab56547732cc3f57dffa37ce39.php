

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
<?php $__env->startSection("content"); ?>
 <div id="content">
        <div class="table-container p-3">
            <div class="row mb-4">
    <div class="col-md-6">
        

        <!-- Add New Teacher -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="bi bi-plus me-2"></i>Add Semester
        </button>
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
      
      <form action="<?php echo e(route('semester.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
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
                        <?php
                        $currentYear = date("Y");
        for ($i = 0; $i < 5; $i++) {
            $start = $currentYear - $i;
            $end = $start + 1;
            echo "<option value='$start/$end'>$start/$end</option>";
                 }
                    ?>
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
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
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
    <?php $i = 1; ?>
    <?php $__empty_1 = true; $__currentLoopData = $sems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $se): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($i++); ?></td>
            <td><?php echo e($se->semName); ?></td>
            <td><?php echo e($se->academic_year); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($se->start_date)->format('d F Y')); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($se->end_date)->format('d F Y')); ?></td>
            <td><?php echo e($se->semCode); ?></td>
            <td>
                <?php if($se->status=="Active"): ?>
                   <span style="color: green"><?php echo e($se->status); ?></span>
                   <?php else: ?>
                    <span style="color: red"><?php echo e($se->status); ?></span>
                    
                <?php endif; ?>

            </td>
            <td>
                 <button
        class="btn btn-sm btn-primary editBtn"
        data-id="<?php echo e($se->id); ?>"
        data-semname="<?php echo e($se->semName); ?>"
        data-academic_year="<?php echo e($se->academic_year); ?>"
        data-start_date="<?php echo e($se->start_date); ?>"
        data-end_date="<?php echo e($se->end_date); ?>"
        data-semcode="<?php echo e($se->semCode); ?>"
        data-bs-toggle="modal"
        data-bs-target="#editSemesterModal">

        <i class="bi bi-pencil-square"></i>
    </button>
    <div class="modal fade" id="editSemesterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="editForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-header">
                    <h5 class="modal-title">Edit Semester</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-3">
                        <label>Semester Name</label>
                        <input
                            type="text"
                            name="semName"
                            id="edit_semName"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Academic Year</label>
                        <input
                            type="text"
                            name="academic_year"
                            id="edit_academic_year"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Start Date</label>
                        <input
                            type="date"
                            name="start_date"
                            id="edit_start_date"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>End Date</label>
                        <input
                            type="date"
                            name="end_date"
                            id="edit_end_date"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Semester Code</label>
                        <input
                            type="text"
                            name="semCode"
                            id="edit_semCode"
                            class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
    
    <!-- Button (trigger modal) -->
<a href="#" 
   class="btn btn-sm btn-success" 
   data-bs-toggle="modal" 
   data-bs-target="#confirmModal" 
   data-url="<?php echo e(route('semester.changeStatus', ['id' => $se->id, 'status' => 'Active'])); ?>"
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="7" class="text-center">No Record Found</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>

            
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const editButtons = document.querySelectorAll('.editBtn');
    const form = document.getElementById('editForm');

    editButtons.forEach(button => {

        button.addEventListener('click', function () {

            let id = this.dataset.id;

            form.action = "/semester/" + id;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_semName').value = this.dataset.semname;
            document.getElementById('edit_academic_year').value = this.dataset.academic_year;
            document.getElementById('edit_start_date').value = this.dataset.start_date;
            document.getElementById('edit_end_date').value = this.dataset.end_date;
            document.getElementById('edit_semCode').value = this.dataset.semcode;

        });

    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/semester.blade.php ENDPATH**/ ?>