<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Dept;
use App\Models\Designation;
use App\Models\External;
use App\Models\Hall;
use App\Models\Role;
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
        $dept = Dept::where('name', Auth::user()->name)->first();
        $sessions = Session::count();
        $teachers = Teacher::where('dept_id', $dept->id)->count();
        $students = Student::where('dept_id', $dept->id)->count();
        $courses = Course::where('dept_id', $dept->id)->count();
        $externals = CourseTeacher::where('dept_id', $dept->id)->count();
        $year_heads = YearHead::where('dept_id', $dept->id)->count();


        return view('dept_office.dashboard', compact('sessions', 'teachers', 'students', 'courses', 'externals', 'year_heads'));
    }
}
