<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Dept;
use App\Models\External;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Year;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExternalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept = Dept::where('name', Auth::user()->name)->first();
        $externals = External::with('session', 'course')->where('dept_id', $dept->id)->get();
        return view('dept_office.external.index', compact('externals', 'dept'));
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
        return view('dept_office.external.create', compact('sessions', 'dept', 'courses', 'teachers', 'years', 'semesters'));
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
            'session_id' => 'required | integer',
            'code' => 'required',
            'course_id' => 'required | integer',
            'external_1' => 'required | integer',
            'external_2' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $external_1 = $request->input('external_1');
        $external_2 = $request->input('external_2');
        $session_id = $request->input('session_id');
        $course_id = $request->input('course_id');

        $dept = Dept::where('name', Auth::user()->name)->first();

        $course_teacher = CourseTeacher::where('dept_id', $dept->id)->where('session_id', $session_id)->where('course_id', $course_id)->first();

        if (isset($course_teacher))
        {
            if (($external_1 == $course_teacher->teacher_id) | ($external_2 == $course_teacher->teacher_id))
            {
                Toastr::error('Course Teacher cant be 2nd or 3rd Examiner!', 'Error');
                return redirect()->back();
            }
        }

        if ($external_1 == $external_2 )
        {
            Toastr::error('2nd & 3rd Examiner cant be same!', 'Error');
            return redirect()->back();
        }

        $check = External::where('dept_id', $dept->id)->where('session_id', $session_id)->where('course_id', $course_id)->count();

        if ($check == 0)
        {
            $external = new External();
            $external->session_id = $session_id;
            $external->dept_id = $dept->id;
            $external->course_id = $course_id;
            $external->external_1 = $external_1;
            $external->external_2 = $external_2;
            $external->save();

            Toastr::success('External Teacher added Successfully!', 'Success');
            return redirect()->route('dept_office.external.index');

        } else {
            Toastr::error('2nd & 3rd Examiner has already been added for this course at this batch!', 'Error');
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\External  $external
     * @return \Illuminate\Http\Response
     */
    public function show(External $external)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\External  $external
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $external = External::with('session', 'course')->findOrFail($id);

        $sessions = Session::latest()->get();
        $dept = Dept::where('name', Auth::user()->name)->first();
        $courses = Course::where('dept_id', $dept->id)->get();
        $teachers = Teacher::where('dept_id', $dept->id)->get();
        $years = Year::all();
        $semesters = Semester::all();
        return view('dept_office.external.edit', compact('external','sessions', 'dept', 'courses', 'teachers', 'years', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\External  $external
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, External $external)
    {
        $inputs = $request->except('_token');
        $rules = [
            'session_id' => 'required | integer',
            'code' => 'required',
            'course_id' => 'required | integer',
            'external_1' => 'required | integer',
            'external_2' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $external_1 = $request->input('external_1');
        $external_2 = $request->input('external_2');
        $session_id = $request->input('session_id');
        $course_id = $request->input('course_id');

        $dept = Dept::where('name', Auth::user()->name)->first();

        $course_teacher = CourseTeacher::where('dept_id', $dept->id)->where('session_id', $session_id)->where('course_id', $course_id)->first();

        if (isset($course_teacher))
        {
            if (($external_1 == $course_teacher->teacher_id) | ($external_2 == $course_teacher->teacher_id))
            {
                Toastr::error('Course Teacher cant be 2nd or 3rd Examiner!', 'Error');
                return redirect()->back();
            }
        }

        if ($external_1 == $external_2 )
        {
            Toastr::error('2nd & 3rd Examiner cant be same!', 'Error');
            return redirect()->back();
        }

        $check = External::where('dept_id', $dept->id)->where('session_id', $session_id)->where('course_id', $course_id)->where('id', '!=', $external->id)->count();

        if ($check == 0)
        {
            $external->session_id = $session_id;
            $external->dept_id = $dept->id;
            $external->course_id = $course_id;
            $external->external_1 = $external_1;
            $external->external_2 = $external_2;
            $external->save();

            Toastr::success('External Teacher Updated Successfully!', 'Success');
            return redirect()->route('dept_office.external.index');

        } else {
            Toastr::error('2nd & 3rd Examiner has already been added for this course at this batch!', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\External  $external
     * @return \Illuminate\Http\Response
     */
    public function destroy(External $external)
    {
        $external->delete();
        Toastr::success('External Teacher deleted Successfully!', 'Success');
        return redirect()->route('dept_office.external.index');
    }
}
