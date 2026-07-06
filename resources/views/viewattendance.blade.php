<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: no-box;
            font-family: Arial, Helvetica, sans-serif;
        }
 .page-wrapper {
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .page-content {
        flex: 1;
    }

    /* TABLE SCROLL FIX */
    .table-scroll {
        overflow-x: auto;
        background: #ffffff;
        border-radius: 6px;
    }

    .table-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        vertical-align: middle;
        font-size: 14px;
    }
        body {
            background: #f8fafc; /* academic light */
            color: #1e293b;
        }

        /* HEADER */
        header {
            background: #0f2a44; /* navy blue */
            color: white;
            padding: 15px 20px;
            border-bottom: 4px solid #c9a227; /* gold line */
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav h1 {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            gap: 18px;
        }

        .nav ul li a {
            color: #f8fafc;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
        }

        .nav ul li a:hover {
            color: #c9a227; /* gold hover */
        }

        /* HERO */
        .hero {
            background: linear-gradient(to right, #0f2a44, #143a5e);
            color: white;
            text-align: center;
            padding: 90px 20px;
        }

        .hero h2 {
            font-size: 34px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 16px;
            max-width: 650px;
            margin: auto;
            color: #e5e7eb;
        }

        /* CONTENT */
        .container {
            padding: 50px 20px;
            max-width: 1100px;
            margin: auto;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #c9a227; /* academic accent */
        }

        .card h3 {
            margin-bottom: 12px;
            color: #0f2a44;
        }

        .card p {
            color: #475569;
        }

        /* FOOTER */
        footer {
            background: #0f2a44;
            color: #e5e7eb;
            text-align: center;
            padding: 18px;
            margin-top: 50px;
        }

        /* RESPONSIVE NAV */
        @media (max-width: 600px) {
            .nav ul {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .hero h2 {
                font-size: 26px;
            }
        }
        .chart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
        }
    </style>
</head>
<body>
   <div class="content">
    <!-- HEADER -->
    <header>
    <div class="nav">
        <h1>Timetable Management System</h1>
      <ul>
        <li><a href="{{ url('/teacher-dash') }}">Home</a></li>
        <li><a href="{{ url('/teachersub') }}">Subjects</a></li>
        <li><a href="{{ url('/teachettbl') }}">Timetable</a></li>
        <li><a href="{{ url('/profile') }}">Profile</a></li>
        <li><a href="{{ url('/attendance') }}">Emergency less</a></li>

        {{-- Logout for teacher --}}
        <li class="ms-auto">
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                 <button type="submit" style="padding: 5px;background-color: #c9a227;border-radius: 5px;color: white;border: none;cursor: pointer">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </li>

</ul>


    </div>
</header>
  <div class="page-content">

        <section class="container mt-5">
            
          

            <!-- TABLE SCROLLABLE WRAPPER -->
            <div class="table-scroll shadow">
                <div style="text-align: center;margin-top: 10px;">
                <img src="{{ asset('images/ipalogo1.png') }}" alt="" width="120">
            </div>
     <h3 style="text-align: center;margin-top: 10px">INSTITUTE OF PUBLIC AND ADMINISTRATION</h3>
                <h5 style="text-align: center">TEACHER SUBJECT: <b>{{ Auth::user()->firstname }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}</b></h5><br>
              <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr style="background-color: #0f2a44">
                            <th colspan="100" style="text-align: center;font-weight: bold;color: white">My subjects Information</th>
                        </tr>
                        <tr>
                            
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Days remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                       
                        @forelse ($absent_lesson as $abs)
<tr>
  
    {{-- SUBJECT --}}
    <td>
        {{ $abs->subject->subjectName }}
    </td>

    {{-- CODE --}}
    <td>{{ $abs->subject->subjectCode }}</td>
    <td>
    <span style="color: red;font-weight: bold">{{ $abs->remaining_days }} Days Remaining</span>
</td>
</tr>
@empty

<tr>
    <td colspan="100%" style="text-align: center">No lesson marked as emergency</td>
</tr>
@endforelse

                     
                        
                    </tbody>
                </table>
            </div>

            </div>

        </section>

    </div>

    <!-- FOOTER (ALWAYS BOTTOM) -->
    <footer class="bg-dark text-light py-3">
        <p class="text-center mb-0">&copy; 2025 ShamisTech. All Rights Reserved.</p>
    </footer>


 
    
    </div>

</body>
</html>
