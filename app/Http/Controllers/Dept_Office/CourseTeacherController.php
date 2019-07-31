<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Dept;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept = Dept::select('id')->where('name', Auth::user()->name)->first();
        $courses = CourseTeacher::with('dept', 'session', 'course', 'teacher')->where('dept_id', $dept->id)->get();
        return view('dept_office.courseTeacher.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sessions = Session::latest()->get();
        $dept = Dept::where('name', Auth::user()->name)->first();
        $courses = Course::where('dept_id', $dept->id)->get();
        $teachers = Teacher::where('dept_id', $dept->id)->get();
        $years = Year::all();
        $semesters = Semester::all();
        return view('dept_office.courseTeacher.create', compact('sessions', 'dept', 'courses', 'teachers', 'years', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseTeacher  $courseTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(CourseTeacher $courseTeacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseTeacher  $courseTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseTeacher $courseTeacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseTeacher  $courseTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseTeacher $courseTeacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseTeacher  $courseTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseTeacher $courseTeacher)
    {
        //
    }
}
