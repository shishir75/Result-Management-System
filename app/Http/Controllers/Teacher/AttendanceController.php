<?php

namespace App\Http\Controllers\Teacher;

use App\Models\CourseTeacher;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index($course_teacher_id)
    {
        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();
        $session = Session::findOrFail($course->session_id);
        $students = Student::where('session', $session->name)->orderBy('class_roll')->get();

        $semester = Semester::where('id', $course->course->year_semester_id)->first();
        $year = Year::where('id', $course->course->year_semester_id)->first();

        return view('teacher.attendance.create', compact('students', 'course', 'semester', 'year'));

    }
}
