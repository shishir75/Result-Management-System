<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Dept;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Year;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept = Dept::where('name', Auth::user()->name)->first();
        $courses = CourseTeacher::with('dept', 'session', 'course', 'teacher')->where('dept_id', $dept->id)->get();
        return view('dept_office.courseTeacher.index', compact('courses', 'dept'));
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
        $inputs = $request->except('_token');
        $rules = [
          'session_id' => 'required',
          'code' => 'required',
          'course_id' => 'required | integer',
          'teacher_id' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dept = Dept::where('name', Auth::user()->name)->first();

        $dept_id = $dept->id;
        $session_id = $request->input('session_id');
        $code = $request->input('code');
        $course_id = $request->input('course_id');
        $teacher_id = $request->input('teacher_id');

        $check = CourseTeacher::where('dept_id', $dept_id)->where('session_id', $session_id)->where('code', $code)->where('course_id', $course_id)->count();

        if ($check == 0)
        {
            $course_teacher = new CourseTeacher();
            $course_teacher->dept_id = $dept_id;
            $course_teacher->session_id = $session_id;
            $course_teacher->code = $code;
            $course_teacher->course_id = $course_id;
            $course_teacher->teacher_id = $teacher_id;
            $course_teacher->save();

            Toastr::success('Course Teacher assign successfully', 'Success');
            return redirect()->route('dept_office.teacher-course.index');


        } else {
            Toastr::error('Course Teacher already assigned!', 'Error');
            return redirect()->back();
        }

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
    public function edit($id)
    {
        $courseTeacher = CourseTeacher::findOrfail($id);

        $sessions = Session::latest()->get();
        $dept = Dept::where('name', Auth::user()->name)->first();
        $courses = Course::where('dept_id', $dept->id)->get();
        $teachers = Teacher::where('dept_id', $dept->id)->get();
        $years = Year::all();
        $semesters = Semester::all();
        return view('dept_office.courseTeacher.edit', compact('courseTeacher','sessions', 'dept', 'courses', 'teachers', 'years', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseTeacher  $courseTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->except('_token');
        $rules = [
            'session_id' => 'required',
            'code' => 'required',
            'course_id' => 'required | integer',
            'teacher_id' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $courseTeacher = CourseTeacher::findOrfail($id);

        $dept = Dept::where('name', Auth::user()->name)->first();

        $dept_id = $dept->id;
        $session_id = $request->input('session_id');
        $code = $request->input('code');
        $course_id = $request->input('course_id');
        $teacher_id = $request->input('teacher_id');

        $check = CourseTeacher::where('dept_id', $dept_id)->where('session_id', $session_id)->where('code', $code)->where('course_id', $course_id)->where('id', '!=', $courseTeacher->id)->count();

        if ($check == 0)
        {
            $course_teacher = new CourseTeacher();
            $course_teacher->dept_id = $dept_id;
            $course_teacher->session_id = $session_id;
            $course_teacher->code = $code;
            $course_teacher->course_id = $course_id;
            $course_teacher->teacher_id = $teacher_id;
            $course_teacher->save();

            Toastr::success('Course Teacher assign updated successfully', 'Success');
            return redirect()->route('dept_office.teacher-course.index');


        } else {
            Toastr::error('Course Teacher already assigned!', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseTeacher  $courseTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CourseTeacher::findOrfail($id)->delete();
        Toastr::success('Course Teacher deleted successfully', 'Success');
        return redirect()->route('dept_office.teacher-course.index');
    }

    public function fetch_course(Request $request)
    {
        $value = $request->get('value');

        $dept = Dept::where('name', Auth::user()->name)->first();
        if ($dept->is_semester == 1)
        {
            $data = Semester::where('code', $value)->first();

        } else {
            $data = Year::where('code', $value)->first();
        }

        $courses = Course::where('dept_id', $dept->id)->where('year_semester_id', $data->id)->get();

        $output = '<option value="" selected disabled>Select Course</option>';

        //$output = '';

        foreach ($courses as $row)
        {
            $output .= '<option value=" '. $row->id .' "> ' . $row->course_code .' - '. $row->course_title .' </option>';
        }

        echo $output;
    }

}
