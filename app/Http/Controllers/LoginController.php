<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Course;
use App\Models\Department;
use App\Models\Loggins;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Models\Semester;
use PhpParser\Node\Stmt\ElseIf_;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function ShowLogin(){
        return view("login");
    }
 public function login(Request $request)
{
    $request->validate([
        "email" => "required|email",
        "password" => "required"
    ]);

    $loginInput = $request->email;
    $password   = trim($request->password);

    
    if (Auth::guard('teacher')->attempt([
        'email' => $loginInput,
        'password' => $password
    ])) {

        $teacher = Auth::guard('teacher')->user();

        if ($teacher->status === 'Blocked') {
            Auth::guard('teacher')->logout();
            return back()->with('custom', 'Your account is blocked.');
        }

        if ($teacher->user_level === "admin") {
            return redirect()->route('dash');
        }

        if ($teacher->role === "Supervisor") {
            return redirect()->route('supdash');
        }

        return redirect()->route('dash1');
    }
    
    if (Auth::guard('cr')->attempt([
        'email' => $loginInput,
        'password' => $password
    ])) {

        return redirect()->route('student.dash');
    }
    return back()->with("custom", "Wrong email or password");
}


    public function showDash() {
    $tcount = Teacher::where("user_level","teacher")->count();
    $courses = Course::all();
    $total = $courses->count();
    $rooms = Room::all();
    $troom = $rooms->count();
    $dept = Department::all();
    $dept1 = $dept->count();
    $all_logs = Loggins::orderBy("created_at","desc")->take(4)->get();
    $activeSemester = Semester::where('status', 'Active')->first();
    if ($activeSemester) {
        $data = DB::table('timetables')
            ->join('days', 'timetables.day_id', '=', 'days.id')
            ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
            ->where('subjects.semester_id', $activeSemester->id)
            ->select('days.day_name', DB::raw('COUNT(timetables.id) as total_periods'))
            ->groupBy('days.day_name', 'days.id')
            ->orderBy(column: 'days.id')
            ->get();
    } else {
        $data = collect();
    }
    $labels = $data->pluck('day_name');
    $periods = $data->pluck('total_periods');

    return view("dashboard", compact("tcount","total","troom","dept1","activeSemester","labels","periods","all_logs"));
}

    public function logout(Request $request)
{
    if (Auth::guard('teacher')->check()) {
        Auth::guard('teacher')->logout();
    } elseif (Auth::guard('cr')->check()) {
        Auth::guard('cr')->logout();
    }

    return redirect()->route('login');
}

    public function logs(){
        $all_logs = Loggins::orderBy("created_at","desc")->get();
        return view("loggs",compact("all_logs"));
    }
}
