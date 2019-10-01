<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Attendance;
use App\Models\CourseTeacher;
use App\Models\Student;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarksController extends Controller
{
    public function index($course_teacher_id)
    {
        $course_teacher = CourseTeacher::with('session', 'course')->findOrFail($course_teacher_id);
        $students = Student::where('dept_id', $course_teacher->dept_id)->where('session', $course_teacher->session->name)->get();

       return view('teacher.marks.index', compact('students', 'course_teacher'));
    }

    public function download($course_teacher_id)
    {
        $course_teacher = CourseTeacher::with('session', 'course')->findOrFail($course_teacher_id);
        $students = Student::where('dept_id', $course_teacher->dept_id)->where('session', $course_teacher->session->name)->get();


        return view('teacher.marks.download', compact('students', 'course_teacher'));
    }
}
