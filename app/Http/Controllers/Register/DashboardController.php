<?php

namespace App\Http\Controllers\Register;

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

        return view('register.dashboard', compact('depts', 'designations', 'halls', 'sessions', 'teachers', 'students', 'courses', 'roles'));
    }
}
