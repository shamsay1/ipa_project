<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #1e3c72;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1e3c72;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .input-group input:focus {
            border-color: #1e3c72;
            outline: none;
            box-shadow: 0 0 0 2px rgba(30, 60, 114, 0.2);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .remember-forgot label {
            display: flex;
            align-items: center;
            color: #666;
        }
        
        .remember-forgot input {
            margin-right: 5px;
        }
        
        .remember-forgot a {
            color: #1e3c72;
            text-decoration: none;
        }
        
        .remember-forgot a:hover {
            text-decoration: underline;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #1e3c72;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2a5298;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .signup-link a {
            color: #1e3c72;
            text-decoration: none;
            font-weight: 600;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 25px;
                margin: 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Timetable Management System</h1>
            <p>Put your Email to reset the password</p>
        </div>
        <?php if(session('error')): ?>
        <div style="text-align: center">
        <span style="color: red;text-align: center"><?php echo e(session('error')); ?></span>
         </div>
        <?php endif; ?>
        <form action="<?php echo e(route('password.email')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <h3 style="text-align:center; margin-bottom:20px;">Forgot Password</h3>

        <?php if(session('success')): ?>
            <div class="alert alert-success" style="color: green;margin-bottom: 4px"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        
       
        
        <button type="submit">Send Link</button>
         <div class="remember-forgot">
            <a href="/">Back to login</a>
        </div>
    </form>
    </div>
</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/resetpassword.blade.php ENDPATH**/ ?>