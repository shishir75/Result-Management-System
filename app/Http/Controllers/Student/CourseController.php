<?php

namespace App\Http\Controllers\Student;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $name = Auth::user()->name;
        $student = Student::where('name', $name)->first();

        $session = Session::where('name', $student->session)->first();

        $courses = CourseTeacher::with('course')->latest()->where('session_id', $session->id)->get();


        return view('student.course', compact('courses'));
    }
}
