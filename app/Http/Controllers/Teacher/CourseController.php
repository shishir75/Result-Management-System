<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();
        $course_teachers = CourseTeacher::with('session', 'course', 'teacher')->where('teacher_id', $teacher->id)->orderBy('id', 'desc')->get();
        return view('teacher.course.index', compact('teacher', 'course_teachers'));
    }

}
