<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management System - Admin Dashboard</title>
     <link rel="icon" type="image/png" href="{{ asset('ipalogo1.png') }}">

<!-- Bootstrap CSS -->

<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
  crossorigin="anonymous"
>

<!-- Bootstrap Icons -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
>

    
    <style>
        body {
            overflow: hidden;
            background-color: #f8f9fa;
        }
        #navbar {
            height: 60px;
            background-color: rgb(57, 57, 196);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        #sidebar {
            width: 220px;
            height: calc(100vh - 60px);
            background-color: #212529;
            position: fixed;
            left: 0;
            top: 60px;
            z-index: 900;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            overflow-y: auto;
        }
        #sidebar .nav-link {
            color: #989fa7;
            padding: 0.6rem 0.8rem;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
            display: flex; /* Tumia flexbox kuweka icon na text vizuri */
            align-items: center;
        }
        #sidebar .nav-link:hover {
            color: #fff;
            background-color: #656b70;
            border-left: 3px solid #0d6efd;
        }
        #sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
            border-left: 3px solid #0d6efd;
        }
        #sidebar .nav-link i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        .sidebar-header {
            padding: 1.2rem 0.8rem;
            color: #fff;
            border-bottom: 1px solid #343a40;
        }
        #content {
            margin-left: 220px;
            margin-top: 60px;
            padding: 20px;
            overflow-y: auto;
            height: calc(100vh - 60px);
            transition: all 0.3s;
        }
        
        /* Staili mpya za Submenu/Accordion */
        #accordionSidebar .nav-item .collapse .nav-link {
            padding-left: 1.8rem; /* Kuweka nafasi kushoto kuonyesha ni submenu */
            font-size: 0.85rem;
            background-color: #2c3136; /* Rangi tofauti kidogo kwa submenu */
            border-left: none; /* Ondoa mpaka wa kushoto */
        }
        #accordionSidebar .nav-item .collapse .nav-link:hover {
            background-color: #343a40; /* Rangi ya hover ya submenu */
            color: #fff;
            border-left: 3px solid #0d6efd; /* Rudi na mpaka wa kushoto kwa hover */
        }
        
        /* Staili ya kuondoa background na border ya Bootstrap Accordion default */
        #accordionSidebar .nav-link.accordion-button {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            color: #989fa7;
        }

        /* Staili ya kugeuza icon ya chevron inaposubmenu inafunguliwa */
        .chevron-icon {
            margin-left: auto; /* Peleka icon kulia */
            font-size: 0.8em;
            transition: transform 0.3s;
        }
        .nav-link.accordion-button[aria-expanded="true"] .chevron-icon {
            transform: rotate(180deg);
        }

        /* Staili ya kuonyesha accordion iliyofunguliwa */
        .nav-link.accordion-button.active-accordion {
            color: #fff;
            background-color: #343a40;
            border-left: 3px solid #0d6efd;
        }

        /* ... CSS zako za awali hapa chini ... */

        .dashboard-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
        }
        .recent-activities {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }
        .activity-item {
            border-left: 3px solid #0d6efd;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .chart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
        }
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -220px;
                width: 220px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                margin-left: 0;
            }
            #sidebar.active ~ #content {
                margin-left: 220px;
            }
            #sidebarCollapse {
                display: block;
            }
        }
    </style>
</head>
<body>
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-1">
    <div class="container-fluid">

        <button id="sidebarCollapse" class="btn btn-outline-light me-2 d-lg-none">
            <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand mx-auto d-none d-lg-block" 
            style="font-size: 22px; color: white; font-family: 'Times New Roman', Times, serif; font-weight: bold;text-align:center">
            Timetable Management System
        </a>

        <a class="navbar-brand mx-auto d-lg-none text-center"
            style="font-size: 18px; color: white; font-family: 'Times New Roman', Times, serif; font-weight: bold;">
            Timetable Management
        </a>

        <div class="d-flex align-items-center ms-auto">
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-light btn-sm fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>

    </div>
</nav>


    <div id="sidebar">
    <div class="sidebar-header">
        <h4 class="m-0" style="font-size: 1.2rem;">Timetable Pro</h4>
    </div>
    <ul class="nav flex-column" id="accordionSidebar"> 

    

    {{-- ===== ADMIN MENU ONLY ===== --}}
   {{-- ===== ADMIN MENU ===== --}}
@auth
   @if(auth()->user()->user_level === 'admin')

    {{-- Dashboard --}}
    <li class="nav-item">
        <a href="{{ url('/dash') }}"
           class="nav-link {{ Route::currentRouteName() == 'dash' ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- Department & Course --}}
    <li class="nav-item">
    <a href="#departmentCourseSubmenu"
       class="nav-link accordion-button collapsed
       {{
            Route::currentRouteName() == 'department.index' ||
            Route::currentRouteName() == 'course.index'
            ? 'active' : ''
       }}"
       data-bs-toggle="collapse"
       data-bs-target="#departmentCourseSubmenu"
       aria-expanded="false">

        <i class="bi bi-layers"></i>
        <span>Department</span>
        <i class="bi bi-chevron-down chevron-icon ms-auto"></i>
    </a>

    <ul class="collapse list-unstyled
        {{
            Route::currentRouteName() == 'department.index' ||
            Route::currentRouteName() == 'course.index'
            ? 'show' : ''
        }}"
        id="departmentCourseSubmenu"
        data-bs-parent="#accordionSidebar">

        <li class="nav-item">
            <a href="{{ route('department.index') }}"
               class="nav-link {{ Route::currentRouteName() == 'department.index' ? 'active' : '' }}">
                <i class="bi bi-journals"></i>
                <span>Department Info</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('course.index') }}"
               class="nav-link {{ Route::currentRouteName() == 'course.index' ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Course Info</span>
            </a>
        </li>

    </ul>
</li>

    {{-- Teachers --}}
    <li class="nav-item">
        <a href="{{ url('/teachers') }}"
           class="nav-link {{ Route::currentRouteName() == 'teachers.index' ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <span>Teachers</span>
        </a>
    </li>

    {{-- Class Management --}}
    <li class="nav-item">
    <a href="#classManagementSubmenu"
       class="nav-link accordion-button collapsed 
       {{ 
            Route::currentRouteName() == 'room.index' || 
            Route::currentRouteName() == 'courseroom.index' 
            ? 'active' : '' 
       }}"
       data-bs-toggle="collapse"
       data-bs-target="#classManagementSubmenu"
       aria-expanded="false">

        <i class="bi bi-door-open"></i>
        <span>Class Management</span>
        <i class="bi bi-chevron-down chevron-icon ms-auto"></i>
    </a>

    <ul class="collapse list-unstyled 
        {{ 
            Route::currentRouteName() == 'room.index' || 
            Route::currentRouteName() == 'courseroom.index' 
            ? 'show' : '' 
        }}"
        id="classManagementSubmenu"
        data-bs-parent="#accordionSidebar">

        <li class="nav-item">
            <a href="{{ route('room.index') }}"
               class="nav-link {{ Route::currentRouteName() == 'room.index' ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Classrooms</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('courseroom.index') }}"
               class="nav-link {{ Route::currentRouteName() == 'courseroom.index' ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Room Assign</span>
            </a>
        </li>

    </ul>
</li>

    {{-- Subjects --}}
    <li class="nav-item">
        <a href="{{ url('/subject') }}" class="nav-link {{ Route::currentRouteName() == 'subject.index' ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i>
            <span>Subjects</span>
        </a>
    </li>

    {{-- Semester --}}
    <li class="nav-item">
        <a href="{{ url('/semester') }}"
           class="nav-link {{ Route::currentRouteName() == 'semester.index' ? 'active' : '' }}">
            <i class="bi bi-calendar2-range"></i>
            <span>Semester</span>
        </a>
    </li>

    {{-- Time Slot --}}
    <li class="nav-item">
        <a href="{{ url('/timeslot') }}"
           class="nav-link {{ Route::currentRouteName() == 'timeslot.index' ? 'active' : '' }}">
            <i class="bi bi-clock"></i>
            <span>Time Slot</span>
        </a>
    </li>
    

    {{-- Timetable Info --}}
<li class="nav-item">
    <a href="#timetableInfoSubmenu"
       class="nav-link accordion-button collapsed
       {{
            Route::currentRouteName() == 'timetable.generate' ||
            Route::currentRouteName() == 'timetable.validate'
            ? 'active' : ''
       }}"
       data-bs-toggle="collapse"
       data-bs-target="#timetableInfoSubmenu"
       aria-expanded="false">

        <i class="bi bi-calendar3"></i>
        <span>Timetable Info</span>
        <i class="bi bi-chevron-down chevron-icon ms-auto"></i>
    </a>

    <ul class="collapse list-unstyled
        {{
            Route::currentRouteName() == 'timetable.generate' ||
            Route::currentRouteName() == 'validate'
            ? 'show' : ''
        }}"
        id="timetableInfoSubmenu"
        data-bs-parent="#accordionSidebar">

        <li class="nav-item">
            <a href="{{ route('timetable.generate') }}"
               class="nav-link {{ Route::currentRouteName() == 'timetable.generate' ? 'active' : '' }}">
                <i class="bi bi-calendar-plus"></i>
                <span>Generate Timetable</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/timetable/validate') }}"
               class="nav-link {{ Route::currentRouteName() == 'validate' ? 'active' : '' }}">
                <i class="bi bi-check2-square"></i>
                <span>View Validation</span>
            </a>
        </li>

    </ul>
</li>

    {{-- Reports --}}
<li class="nav-item">
    <a href="#reportsSubmenu"
       class="nav-link accordion-button collapsed
       {{
            Route::currentRouteName() == 'report1' ||
            Route::currentRouteName() == 'report' ||
            Route::currentRouteName() == 'roomusage'
            ? 'active' : ''
       }}"
       data-bs-toggle="collapse"
       data-bs-target="#reportsSubmenu"
       aria-expanded="false">

        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Reports</span>
        <i class="bi bi-chevron-down chevron-icon ms-auto"></i>
    </a>

    <ul class="collapse list-unstyled
        {{
            Route::currentRouteName() == 'report1' ||
            Route::currentRouteName() == 'report' ||
            Route::currentRouteName() == 'roomusage'
            ? 'show' : ''
        }}"
        id="reportsSubmenu"
        data-bs-parent="#accordionSidebar">

        <li class="nav-item">
            <a href="{{ url('/report1') }}"
               class="nav-link {{ Route::currentRouteName() == 'report1' ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i>
                <span>Teacher Subjects</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/report') }}"
               class="nav-link {{ Route::currentRouteName() == 'report' ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i>
                <span>Teacher Workload</span>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a href="{{ url('/roomusage') }}"
               class="nav-link {{ Route::currentRouteName() == 'roomusage' ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Room Utilization</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('roomReport') }}"
               class="nav-link {{ Route::currentRouteName() == 'roomusage' ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Room's Timetable</span>
            </a>
        </li>
        

    </ul>
</li>
<li class="nav-item">
    <a href="/teachersub1" 
       class="nav-link {{ request()->routeIs('teachersub1') ? 'active' : '' }}">
        <i class="bi bi-journal-bookmark"></i> My Subjects
    </a>
</li>

<li class="nav-item">
    <a href="/teachertbl1" 
       class="nav-link {{ request()->routeIs('teachertbl1') ? 'active' : '' }}">
        <i class="bi bi-table"></i> My Timetable
    </a>
</li>


    {{-- Profile --}}
    <li class="nav-item">
        <a href="{{ url('/adminprofile') }}"
           class="nav-link {{ Route::currentRouteName() == 'adminprofile.index' ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Setting</span>
        </a>
    </li>

@endif
@endauth

   @auth
    @if(auth()->user()->role === 'Supervisor' || auth()->user()->role === 'naibu')

<li class="nav-item">
    <a href="{{ route('supdash') }}" 
       class="nav-link {{ request()->routeIs('supdash') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
</li>

{{-- <li class="nav-item">
    <a href="{{ route('cr_info.index') }}" 
       class="nav-link {{ request()->routeIs('cr_info.index') ? 'active' : '' }}">
        <i class="bi bi-person-plus-fill"></i> Registration
    </a>
</li> --}}

<li class="nav-item">
    <a href="/supervision" 
       class="nav-link {{ request()->routeIs('supervision') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Todays Sessions
    </a>
</li>

<li class="nav-item">
    <a href="/teachersub1" 
       class="nav-link {{ request()->routeIs('teachersub1') ? 'active' : '' }}">
        <i class="bi bi-journal-bookmark"></i> Subjects
    </a>
</li>

<li class="nav-item">
    <a href="/teachertbl1" 
       class="nav-link {{ request()->routeIs('teachertbl1') ? 'active' : '' }}">
        <i class="bi bi-table"></i> Timetable
    </a>
</li>

<li class="nav-item">
    <a href="/report7" 
       class="nav-link {{ request()->routeIs('report7') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i> Subject attendance
    </a>
</li>
<li class="nav-item">
    <a href="/report8" 
       class="nav-link {{ request()->routeIs('report8') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i> Teacher's attendance
    </a>
</li>

<li class="nav-item">
    <a href="/adminprofile" 
       class="nav-link {{ Route::currentRouteName() == 'sup' ? 'active' : '' }}">
        <i class="bi bi-gear"></i> Setting
    </a>
</li>

@endif
@endauth
    {{-- ===== END ADMIN MENU ===== --}}

    {{-- ===== TEACHER MENU ===== --}}
    {{-- @auth
    @if(auth()->user()->user_level === 'teacher' && auth()->user()->role != "Supervisor")

        <li class="nav-item">
            <a href="{{ route('dash1') }}" 
               class="nav-link {{ request()->routeIs('dash1') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('teachersub') }}" 
               class="nav-link {{ request()->routeIs('teachersub') ? 'active' : '' }}">
                <i class="bi bi-book"></i> My Subject
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('teachettbl') }}" 
               class="nav-link {{ request()->routeIs('teachettbl') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> My Timetable
            </a>
        </li>

    @endif
@endauth --}}
    {{-- ===== END TEACHER MENU ===== --}}

    
    </ul>


</div>


    @yield("content")

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar kwa screen ndogo
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            // LOGIC YA KUWEKA STAIILI YA ACTIVE KWENYE ACCORDION ITEM
            // Inahakikisha kwamba item iliyofunguliwa inaonekana "active"
            $('.accordion-button').on('click', function() {
                var targetId = $(this).attr('data-bs-target');
                var isExpanded = $(this).attr('aria-expanded') === 'true';

                // Ondoa active-accordion kwa accordion zote
                $('.accordion-button').removeClass('active-accordion');

                // Ongeza active-accordion ikiwa inafunguliwa (haitakiwi)
                // Au iondoe ikiwa inafungwa
                if (!isExpanded) {
                     // Submenu inafungwa, hapo hatufanyi chochote, inarudi kuwa inactive.
                } else {
                    // Submenu inafunguliwa, weka active-accordion style
                    $(this).addClass('active-accordion');
                }

                 // Logik ya kuweka active-accordion wakati inafunguliwa
                setTimeout(() => {
                    if ($(targetId).hasClass('show')) {
                        $(this).addClass('active-accordion');
                    } else {
                        $(this).removeClass('active-accordion');
                    }
                }, 300); // Muda mfupi baada ya Bootstrap kumaliza animation

            });
        });
    </script>
</body>
</html>