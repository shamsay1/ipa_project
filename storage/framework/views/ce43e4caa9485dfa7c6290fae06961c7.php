

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
    <div class="row align-items-center mb-4">
    <div class="col-md-7 d-flex flex-wrap align-items-center mb-3 mb-md-0 gap-2">
        <form action="<?php echo e(route('class.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
            <?php echo csrf_field(); ?>
            <input type="file" name="class_file" id="excelFile" accept=".xls,.xlsx" onchange="document.getElementById('importForm').submit();">
        </form>
        <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click();">
            <i class="bi bi-upload me-2"></i>Import Excel
        </button>

        <a href="<?php echo e(route('class.template')); ?>" class="btn btn-info text-white">
            <i class="bi bi-download me-2"></i>Download Template
        </a>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Class
        </button>
    </div>

    <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search teachers..." autocomplete="off">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                    </div>
                </div>
</div>
</div>

<!-- Modal for Adding Teacher -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary">
        <h5 class="modal-title fw-bold text-white" id="addTeacherModalLabel">Add New Class</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="<?php echo e(route('room.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="firstname" class="form-label">Class Name</label>
                    <input type="text" class="form-control" name="classname">
                    <?php $__errorArgs = ["classname"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: red"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lastname" class="form-label">Capacity</label>
                    <select class="form-select" name="capacity">
                        <option value="">-- Select Type --</option>
                        <option value="Normal">Normal</option>
                        <option value="Hall">Hall</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="gender" class="form-label">Type</label>
                    <select class="form-select" name="type">
                        <option value="">-- Select Type --</option>
                        <option value="Normal">Normal</option>
                        <option value="Lab">Lab</option>
                    </select>
                    <?php $__errorArgs = ["type"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: red"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="mobile" class="form-label">FLOOR</label>
                    <select name="building_id" class="form-select">
                        <option value="">-- Select Type --</option>
                        <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($bu->id); ?>"><?php echo e($bu->building_name); ?></option>
                            
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    </select>
                </div>
                <div class="col-md-6">
                    <label for="gender" class="form-label">Practical Type</label>
                    <select class="form-select" name="practical_type">
                        
                        <option value="">-- Select Practical --</option>
                        <option value="Normal">Normal</option>
                        <option value="Computer Lab">Computer Lab</option>
                        <option value="Skill Lab">Skill Lab</option>
                        <option value="Compounding Lab">Compounding Lab</option>

                    </select>
                    <?php $__errorArgs = ["type"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: red"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
 <div class="container">

    <!-- Display Validation Errors -->
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul style="list-style: none">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    </div>
            <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Class Information</h6>
      <p class="mb-0" style="color: green"><?php echo e(session('success')); ?></p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>C/N</th>
                            <th>Class Name</th>
                            <th>Capacity</th>
                            <th>Type</th>
                            <th>Practical Type</th>
                            <th>Status</th>
                       
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="roomTableBody">
                        <?php echo $__env->make('partials.room_table', ['rooms' => $rooms], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        
                        <div class="mt-3">
                            <?php echo e($rooms->links()); ?>

                        </div>
                        
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val();

        $.ajax({
            url: "<?php echo e(route('room.index')); ?>",
            type: "GET",
            data: { search: query },
            beforeSend: function() {
                $('#roomTableBody').html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"></div> Searching...</td></tr>');
            },
            success: function(data) {
                $('#roomTableBody').html(data);
            },
            error: function() {
                $('#roomTableBody').html('<tr><td colspan="7" class="text-danger text-center">Error loading data.</td></tr>');
            }
        });
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/class.blade.php ENDPATH**/ ?>