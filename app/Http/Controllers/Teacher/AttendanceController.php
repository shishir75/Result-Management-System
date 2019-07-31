<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index($course_teacher_id)
    {
        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();
        $session = Session::findOrFail($course->session_id);
        $students = Student::where('session', $session->name)->orderBy('class_roll')->get();

        return view('teacher.attendance.create', compact('students', 'course'));

    }
}
