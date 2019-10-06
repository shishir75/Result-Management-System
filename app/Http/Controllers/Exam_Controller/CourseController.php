<?php

namespace App\Http\Controllers\Exam_Controller;

use App\Models\Dept;
use App\Models\FinalMarks;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function session($slug)
    {
        $dept = Dept::where('slug', $slug)->first();
        if (isset($dept))
        {
            $courses = FinalMarks::with('session', 'dept')->where('dept_id', $dept->id)->where('teacher_1_marks', '!=', null)->where('teacher_2_marks', '!=', null)->distinct()->get(['session_id', 'dept_id']);
            return view('exam_controller.session.index', compact('courses', 'dept'));

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }

    }

    public function year_semester($slug, $session_id)
    {
        $dept = Dept::where('slug', $slug)->first();

        if (isset($dept) && isset($session_id))
        {
            $courses = FinalMarks::with('session', 'dept', 'course')->where('dept_id', $dept->id)->where('session_id', $session_id)->where('teacher_1_marks', '!=', null)->where('teacher_2_marks', '!=', null)->distinct()->get(['session_id', 'dept_id', 'course_id']);
            return view('exam_controller.year_semester.index', compact('courses', 'dept'));

        } else {
            Toastr::error('Invalid URL!', 'Error');
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
