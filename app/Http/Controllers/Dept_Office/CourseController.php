<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Course;
use App\Models\Dept;
use App\Models\Semester;
use App\Models\Year;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept_name = Auth::user()->name;
        $dept = Dept::select('id', 'is_semester')->where('name', $dept_name)->first();
        $courses = Course::with('semester', 'year')->where('dept_id', $dept->id)->orderBy('course_code')->get();

        return view('dept_office.course.index', compact('courses', 'dept_name', 'dept'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $semesters = Semester::all();
        $years = Year::all();

        $dept_name = Auth::user()->name;
        $dept = Dept::select('is_semester')->where('name', $dept_name)->first();

        return view('dept_office.course.create', compact('semesters', 'years', 'dept'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
          'course_title' => 'required',
          'course_code' => 'required',
          'year_semester_id' => 'required | integer',
          'credit_hour' => 'required | numeric',
          'incourse_marks' => 'required | integer',
          'final_marks' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dept_name = Auth::user()->name;
        $dept = Dept::select('id')->where('name', $dept_name)->first();

        $course = new Course();
        $course->course_title = $request->input('course_title');
        $course->course_code = $request->input('course_code');
        $course->year_semester_id = $request->input('year_semester_id');
        $course->credit_hour = $request->input('credit_hour');
        $course->incourse_marks = $request->input('incourse_marks');
        $course->final_marks = $request->input('final_marks');
        $course->dept_id = $dept->id ;
        if ($request->input('is_lab'))
        {
            $course->is_lab = 1;
        } else {
            $course->is_lab = 0;
        }
        $course->save();

        Toastr::success('Course added Successfully', 'Success!!!');
        return redirect()->route('dept_office.course.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $semesters = Semester::all();
        return view('dept_office.course.edit', compact('course', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $inputs = $request->except('_token');
        $rules = [
            'course_title' => 'required',
            'course_code' => 'required',
            'year_semester_id' => 'required | integer',
            'credit_hour' => 'required | numeric',
            'incourse_marks' => 'required | integer',
            'final_marks' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $course->course_title = $request->input('course_title');
        $course->course_code = $request->input('course_code');
        $course->year_semester_id = $request->input('year_semester_id');
        $course->credit_hour = $request->input('credit_hour');
        $course->incourse_marks = $request->input('incourse_marks');
        $course->final_marks = $request->input('final_marks');

        if ($request->input('is_lab'))
        {
            $course->is_lab = 1;
        } else {
            $course->is_lab = 0;
        }
        $course->save();

        Toastr::success('Course updated Successfully', 'Success!!!');
        return redirect()->route('dept_office.course.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        Toastr::success('Course deleted Successfully', 'Success!!!');
        return redirect()->route('dept_office.course.index');
    }
}
