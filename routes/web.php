<?php

use App\Exports\Course_RoomsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ApiController,
    CourseController,
    DepartmentController,
    LoginController,
    ReportController,
    RoomController,
    SemesterController,
    SubjectController,
    TeacherController,
    TimeslotController,
    CourseRoomController,
    CrInfoController,
    ResestController,
    StudentController,
    TimetableGeneratorController
};
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/



Route::get("/lessons",[CrInfoController::class,"lessons"])->name("lessons");

Route::get("/studenttbl",[CrInfoController::class,"studentTimetable"])->name("studenttbl");

Route::post('/teacher-attendance/store',
        [CrInfoController::class,'store1'])
        ->name('teacher.attendance.store');

Route::post('/teacher/emergency', [TeacherController::class, 'markEmergency'])
    ->name('teacher.emergency');

Route::get("/message",[TimetableGeneratorController::class,"message"])->name("message");
Route::get("/", [LoginController::class,"ShowLogin"])->name("again");
Route::get('/login', [LoginController::class, 'ShowLogin'])->name('login');
Route::post("/login",[LoginController::class,"Login"]);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [ResestController::class, 'reset'])->name('password.request');
Route::post('/forgot-password', [ResestController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResestController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResestController::class, 'updatePassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:teacher', 'admin'])->group(function () {
Route::post('/import-course-rooms', [CourseRoomController::class, 'importCourseRooms'])->name("cimport");
    Route::get('/export-course-rooms-template', function () {
    return Excel::download(new Course_RoomsExport(Auth::user()->branch_id), 'course_rooms_template.xlsx');
});
    Route::get("/teachettbl",[TeacherController::class,"teacherTimetable"])->name("teachettbl");
    Route::get('/roomreport',[ReportController::class,"roomReport"])->name("roomReport");
    Route::get("/teachersub1",[TeacherController::class,"teachersubjects1"])->name("teachersub1");
    Route::get("/dash",[LoginController::class,"showDash"])->name("dash");
    Route::post('/sync-group-subjects',
    [TimetableGeneratorController::class,'syncGroupSubjects'])
    ->name('sync.group.subjects');
    Route::post('/check-solutions', [TimetableGeneratorController::class, 'checkSolutions']);
Route::post('/insert-timetable', [TimetableGeneratorController::class, 'insertTimetable']);
    Route::post('/send-email', [TimetableGeneratorController::class, 'sendEmail'])
    ->name('send.email');
    Route::resource("/teachers",TeacherController::class);
    Route::patch('teachers/{id}/block', [TeacherController::class, 'block'])->name('teachers.block');
    Route::patch('teachers/{id}/unblock', [TeacherController::class, 'unblock'])->name('teachers.unblock');
    Route::resource("/courseroom",CourseRoomController::class);
    Route::get("/logs",[LoginController::class,'logs']);
    Route::get("/adminprofile",[TeacherController::class,"adprofile"]);
    Route::get('/teacher/{id}/subjects', [TeacherController::class, 'viewtsub'])
    ->name('teacher.subjects');
    Route::post("/timetable/enable",[TimetableGeneratorController::class,"enable"]);
    Route::post("/timetable/disable",[TimetableGeneratorController::class,"disable"]);
   Route::get('/print-all-timetables', [TimetableGeneratorController::class, 'printAll'])
    ->name('print.all.timetables');
    Route::get('/print-teachers', [TimetableGeneratorController::class, 'printAllTeachers'])
    ->middleware('auth')->name('print.all.teachers');
    Route::get('/export-teachers-subjects', [TimetableGeneratorController::class, 'exportTeachersSubjects']);
});


/*
|--------------------------------------------------------------------------
| TEACHER (ALL LOGGED TEACHERS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:teacher'])->group(function () {

    Route::get("/teacher-dash",[TeacherController::class,"teacherDash"])->name("dash1");
    Route::get("/superdash",[TeacherController::class,"superdash"])->name("supdash");

    Route::resource('cr_info', CrInfoController::class);
    Route::get("/supervision",[TeacherController::class,"supervision"])->name("supervision");
    Route::get("/report7",[TeacherController::class,"report7"])->name("report7");
    Route::get('/subject-attendance-details', [TeacherController::class, 'getAttendanceDetails'])->name('subject.attendance.details');
    Route::get("/adminprofile",[TeacherController::class,"adprofile"])->name("sup");
    Route::put('/cr-update/{id}',[CrInfoController::class,'update'])->name('cr.update');
    Route::get("/teachertbl1",[TeacherController::class,"teacherTimetable1"])->name("teachertbl1");

    Route::get("/teachettbl",[TeacherController::class,"teacherTimetable"])->name("teachettbl");

    Route::get("/teachersub1",[TeacherController::class,"teachersubjects1"])->name("teachersub1");
 

    Route::get("/teachersub",[TeacherController::class,"teachersubject"])->name("teachersub");
    Route::post("/importt",[TeacherController::class,"TeacherImport"])->name("teacher.import");
    Route::post("/importstudent",[CrInfoController::class,"StudentImport"])->name("student.import");
    Route::get("/search",[TeacherController::class,"search"])->name("teacher.search");
    Route::get("/profile",[TeacherController::class,"profile"])->name("adminprofile.index");
    Route::put('/profile/update', [TeacherController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [TeacherController::class, 'changePassword'])->name('profile.changePassword');
    Route::get('/teacher-timetable/{id}', [TimetableGeneratorController::class, 'viewTeacherTimetable'])->name('teacher.timetable');

    Route::resource('/department', DepartmentController::class);
    Route::resource("/course", CourseController::class)->except("show","create");
    Route::resource("/room",RoomController::class);
    Route::resource("/semester", SemesterController::class);
    Route::resource("/subject", SubjectController::class)->except("show","create");
    Route::resource("/timeslot", TimeslotController::class);
    Route::resource('/course-classrooms', CourseRoomController::class);

    Route::post("/roomImport",[RoomController::class,"classImport"])->name("class.import");
    Route::post("/subject/import",[SubjectController::class,"import"])->name("subject.import");
    Route::post("/course/import",[CourseController::class,"import"])->name("course.import");

    Route::get("/teacher/template",[TeacherController::class,"template"])->name("teacher.template");
    Route::get("/student/template",[CrInfoController::class,"template"])->name("student.template");
    Route::get("/subject/template",[SubjectController::class,"subjectTemplate"])->name("subject.template");
    Route::get("/class/template",[RoomController::class,"roomTemplate"])->name("class.template");
    Route::get("/course/template",[CourseController::class,"export"])->name("course.template");

    Route::get('/semester/{id}/status/{status}', [SemesterController::class, 'changeStatus'])->name('semester.changeStatus');

    /*
    |--------------------------------------------------------------------------
    | TIMETABLE
    |--------------------------------------------------------------------------
    */
    Route::post('/available-rooms', [TimetableGeneratorController::class, 'availableRooms'])
    ->name('timetable.availableRooms');

    Route::post('/solve-nta-double-booking', [TimetableGeneratorController::class, 'solveNtaDoubleBooking'])
    ->name('timetable.solveNtaDoubleBooking');
    Route::get('/timetable/generate', [TimetableGeneratorController::class, 'showGenerateForm'])->name('timetable.generate');
    Route::post('/generate-timetable', [TimetableGeneratorController::class, 'generateTimetable'])->name('generate.timetable');
    Route::get('/timetable/simple', [TimetableGeneratorController::class, 'viewSimpleTimetable'])->name('timetable.simple');
    Route::get('/timetable/validate', [TimetableGeneratorController::class, 'validateTimetable'])->name("validate");
    Route::get("/timetable/edit/{id}",[TimetableGeneratorController::class,"showedit"])->name("timetable.edit");
    Route::put("/timetable/update/{id}",[TimetableGeneratorController::class,"update"])->name("timetable.update");
    Route::post('/validate-timetable', [TimetableGeneratorController::class, 'checkConflicts'])->name('timetable.checkConflicts');
    Route::post('/timetable/filter-options', [TimetableGeneratorController::class, 'filterOptions'])->name('timetable.filterOptions');
    Route::post('/timetable/checkSolutions', [TimetableGeneratorController::class, 'checkSolutions'])->name('timetable.checkSolutions');
    Route::post('/timetable/solve-conflicts', [TimetableGeneratorController::class, 'solveConflicts'])->name('timetable.solveConflicts');
    Route::post('/timetable/solve-conflicts1', [TimetableGeneratorController::class, 'solveConflicts1'])->name('timetable.solveConflicts1');
    Route::get('/timetable/export-all', [TimetableGeneratorController::class, 'exportAll']);
    Route::get('/timetable/download/{type}', [TimetableGeneratorController::class, 'downloadTimetable'])->name('timetable.download');
    Route::get("/viewall",[TimetableGeneratorController::class,"viewInstitutionTimetable"]);
    Route::post('/reduce-evening', [TimetableGeneratorController::class,"reduceEveningSessions"])->name('reduce.evening');

    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */

    Route::get("/report",[ReportController::class,"report"]);
    Route::get("/report1",[ReportController::class,"report1"]);
    Route::get('/teacher/load-report', [ReportController::class, 'loadReport'])->name('teacher.load.report');
    Route::get('/teacher/load-report1', [ReportController::class, 'teacherLoadReport'])->name('teacher.load.report1');
    Route::get('/roomusage', [ReportController::class, 'index1'])->name('room.usage');

});

/*
|--------------------------------------------------------------------------
| STUDENT / COURSE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:cr'])->group(function () {
    Route::get("/studentDash",[StudentController::class,"index"])->name("student.dash");
    Route::get("/studentSub",[CrInfoController::class,"studentsub"]);
});
