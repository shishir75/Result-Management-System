<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Session;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FinalMarksController extends Controller
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
        return view('teacher.finalMarks.index', compact('teacher', 'course_teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($session_id, $course_id)
    {
        $session = Session::findOrFail($session_id);
        $course = Course::with('dept')->findOrFail($course_id);

        $teacher = Teacher::where('name', Auth::user()->name)->first();

        $course_teacher_check = CourseTeacher::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('teacher_id', $teacher->id)->count();

        if ($course_teacher_check === 1)
        {
            return view('teacher.finalMarks.create', compact('course', 'session'));

        } else {
            Toastr::error('Unauthorized Access Denied!', 'Error');
            return redirect()->back();
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
