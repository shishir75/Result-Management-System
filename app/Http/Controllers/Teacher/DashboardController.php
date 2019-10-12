<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\External;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\YearHead;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $courses = CourseTeacher::where('teacher_id', $teacher->id)->count();
        $second_examiner = External::where('external_1', $teacher->id)->count();
        $third_examiner = External::where('external_2', $teacher->id)->count();
        $year_head = YearHead::where('teacher_id', $teacher->id)->count();

        return view('teacher.dashboard', compact('courses', 'second_examiner', 'third_examiner', 'year_head'));
    }
}
