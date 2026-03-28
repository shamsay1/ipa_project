

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
        background-color: #d1e7dd;
        border-color: #badbcc;
        color: #0f5132;
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
        color: #28a745;
    }
    /* CSS for vertical text in day cells */
    .days strong {
        display: inline-block;
        white-space: nowrap;
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
    
    /* Print-specific styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .print-header {
            display: block !important;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .no-print {
            display: none !important;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            background-color: white !important;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 8px;
            background-color: white !important;
            color: black !important;
        }
        .table thead th {
            background-color: white !important;
            color: black !important;
            -webkit-print-color-adjust: exact;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: white !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: white !important;
        }
        .table-success {
            background-color: white !important;
        }
        /* Remove all colors and shadows */
        .table-container {
            background-color: white !important;
            box-shadow: none !important;
        }
        /* Ensure all text is black */
        * {
            color: black !important;
        }
    }
</style>

<?php $__env->startSection("content"); ?>
<div id="content">
    <div class="table-container p-3">
        <!-- Flash Message -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">
                    <i class="bi bi-person-fill me-1"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#editProfile" role="tab">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                    <i class="bi bi-lock-fill me-1"></i> Change Password
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#nonTeaching" role="tab">
                    <i class="bi bi-calendar-x-fill me-1"></i> Non-Teaching Days
                </a>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content mt-3">
            <!-- PROFILE TAB -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="card p-3">
                    <h5 class="text-primary mb-3">Profile Information</h5>
                    <p><strong class="text-success">Full Name:</strong> <?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?></p>
                    <p><strong class="text-success">Mobile:</strong> <?php echo e(Auth::user()->mobile); ?></p>
                    <p><strong class="text-success">Gender:</strong> <?php echo e(Auth::user()->gender); ?></p>
                    <p><strong class="text-success">Code:</strong> <?php echo e(Auth::user()->teacher_code); ?></p>
                    <p><strong class="text-success">Role:</strong> <?php echo e(Auth::user()->user_level); ?></p>
                </div>
            </div>

            <!-- EDIT PROFILE TAB -->
            <div class="tab-pane fade" id="editProfile" role="tabpanel">
                <div class="card p-3">
                    <h5 class="text-primary mb-3">Edit Profile</h5>
                    <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" value="<?php echo e(Auth::user()->firstname); ?>" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="lastname" value="<?php echo e(Auth::user()->lastname); ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="<?php echo e(Auth::user()->email); ?>" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" value="<?php echo e(Auth::user()->mobile); ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="Male" <?php echo e(Auth::user()->gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="Female" <?php echo e(Auth::user()->gender == 'Female' ? 'selected' : ''); ?>>Female</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>

            <!-- CHANGE PASSWORD TAB -->
            <div class="tab-pane fade" id="changePassword" role="tabpanel">
                <div class="card p-3">
                    <h5 class="text-primary mb-3">Change Password</h5>
                    <form action="<?php echo e(route('profile.changePassword')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update Password</button>
                    </form>
                </div>
            </div>
            <!-- NON TEACHING DAYS TAB -->
<div class="tab-pane fade" id="nonTeaching" role="tabpanel">
    <div class="card p-3">
        <h5 class="text-primary mb-3">Manage Non-Teaching Days</h5>

        <form action="<?php echo e(route('holidays.toggle')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Select Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Pause / Activate Day
            </button>
        </form>

    </div>
</div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("layout.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/adminprofile.blade.php ENDPATH**/ ?>