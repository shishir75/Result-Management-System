<?php

namespace App\Http\Controllers\Exam_Controller;

use App\Models\Course;
use App\Models\Dept;
use App\Models\Designation;
use App\Models\Hall;
use App\Models\Role;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $depts = Dept::count();
        $sessions = Session::count();
        $halls = Hall::count();
        $designations = Designation::count();
        $teachers = Teacher::count();
        $students = Student::count();
        $courses = Course::count();
        $roles = Role::count();

        return view('exam_controller.dashboard', compact('designations', 'depts', 'halls', 'sessions', 'teachers', 'students', 'courses', 'roles'));
    }
}
