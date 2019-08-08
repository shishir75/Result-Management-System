<?php

namespace App\Http\Controllers\Student;

use App\Models\CourseTeacher;
use App\Models\Session;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $name = Auth::user()->name;
        $student = Student::where('name', $name)->first();

        $session = Session::where('name', $student->session)->first();

        $courses = CourseTeacher::with('course', 'teacher')->where('session_id', $session->id)->where('dept_id', $student->dept_id)->orderBy('code', 'desc')->get();

        return view('student.course', compact('courses'));
    }
}
