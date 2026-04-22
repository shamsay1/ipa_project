<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>

    <!-- Bootstrap (optional but helpful) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .card-box{
            background: #fff;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h3{
            font-size: 18px;
            font-weight: bold;
        }

        p{
            color: red;
            font-style: italic;
            margin-top: 10px;
        }

        .btn-logout{
            margin-top: 20px;
        }

        /* Mobile tweaks */
        @media(max-width: 576px){
            .card-box{
                padding: 20px 15px;
            }

            h3{
                font-size: 16px;
            }

            p{
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="card-box">
        <h3>INSTITUTE OF PUBLIC ADMINISTRATION (IPA)</h3>

        <p>
            Timetable is under maintenance,<br>
            please wait until the administrator finishes.
        </p>

        <!-- Logout Form -->
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-danger btn-sm btn-logout">
                Logout
            </button>
        </form>
    </div>

</body>
</html><?php /**PATH C:\Users\PC\Documents\Timetable\resources\views/message.blade.php ENDPATH**/ ?>